<?php

namespace App\Services\Litecom;

use App\Models\LitecomZone;
use App\Models\Reservation;
use Carbon\CarbonInterface;

class LightAutomationService
{
    public function __construct(
        protected LitecomService $litecomService
    )
    {
    }

    public function run(CarbonInterface $now, bool $dryRun = false)
    {
        $turnedOn = $turnedOff = $skipped = 0;

        $zones = LitecomZone::with('courts')->get();

        foreach ($zones as $zone) {
            if ($zone->manual_override_until && $zone->manual_override_until->isFuture()) {
                $skipped++;
                continue;
            }

            $inOnWindow = $this->isInAutoOnWindow($zone, $now);
            if (!$inOnWindow && $zone->active_scene !== 0) {

                if ($zone->manual_override_until && $zone->manual_override_until->isPast() && ($zone->manual_override_source === 'inferred' || $zone->manual_override_source === 'admin')) {
                    $ok = $this->setScene($zone, 0, $dryRun);

                    $ok ? $turnedOff++ : $skipped++;
                    continue;
                }

                if (!$zone->manual_override_until || $zone->manual_override_until->isPast()) {
                    $zone->forceFill([
                        'manual_override_until' => $this->inferManualOverrideUntil($zone, $now),
                        'manual_override_source' => 'inferred',
                    ])->saveQuietly();
                }

                $skipped++;
                continue;
            }

            if ($inOnWindow) {
                if ($zone->active_scene === 0) {
                    $ok = $this->setScene($zone, $zone->auto_scene, $dryRun);

                    $ok ? $turnedOff++ : $skipped++;
                } else {
                    $skipped++;
                }
            }

            $windowTolerance = 1;
            $endFrom = $now->copy()->subMinutes($zone->auto_turn_off_after + $windowTolerance);
            $endTo = $now->copy()->subMinutes($zone->auto_turn_off_after);

            $offCandidates = $this->reservationsForZoneEndedWithin($zone, $endFrom, $endTo);

            foreach ($offCandidates as $res) {
                $end = $res->end_time;
                $targetOffAt = $end->copy()->addMinutes($zone->auto_turn_off_after);

                if ($this->shouldKeepOnUntilNext($zone, $end, $targetOffAt, $zone->auto_turn_on_before)) {
                    $skipped++;
                    continue;
                }

                if ($zone->manual_override_until && $zone->manual_override_until->isFuture()) {
                    $skipped++;
                    continue;
                }

                if ($zone->active_scene === 0) {
                    $skipped++;
                    continue;
                }

                $ok = $this->setScene($zone, 0, $dryRun);

                $ok ? $turnedOff++ : $skipped++;
            }
        }

        return compact('turnedOn', 'turnedOff', 'skipped');
    }

    protected function isInAutoOnWindow(LitecomZone $zone, CarbonInterface $now): bool
    {
        return Reservation::query()
            ->whereNull('canceled_at')
            ->whereIn('court_id', $zone->courts_ids)
            ->whereBetween('start_time', [$now, $now->copy()->addMinutes($zone->auto_turn_on_before)])
            ->exists();
    }

    protected function nextAutoOnOpensAt(LitecomZone $zone, CarbonInterface $now): ?CarbonInterface
    {
        $next = Reservation::query()
            ->whereNull('canceled_at')
            ->where('start_time', '>', $now)
            ->whereIn('court_id', $zone->courts_ids)
            ->orderBy('start_time')
            ->first();

        return $next?->start_time->copy()->subMinutes($zone->auto_turn_on_before);
    }

    protected function inferManualOverrideUntil(LitecomZone $zone, CarbonInterface $now): CarbonInterface
    {
        $opensAt = $this->nextAutoOnOpensAt($zone, $now);

        if ($opensAt && $opensAt->gt($now)) {
            $until = $opensAt->copy()->subMinutes(1);

            $maxCap = $now->copy()->addHours(2);

            return $until->min($maxCap);
        }

        return $now->copy()->addHours(2);
    }

    protected function shouldKeepOnUntilNext(
        LitecomZone     $zone,
        CarbonInterface $endedAt,
        CarbonInterface $targetOffAt,
        int             $onWindowMinutes
    ): bool
    {
        $next = Reservation::query()
            ->whereNull('canceled_at')
            ->where('start_time', '>=', $endedAt)
            ->whereIn('court_id', $zone->courts_ids)
            ->orderBy('start_time')
            ->first();

        if (!$next) {
            return false;
        }

        $nextStart = $next->start_time;
        $nextOnOpensAt = $nextStart->copy()->subMinutes($onWindowMinutes);

        if ($nextStart->lte($targetOffAt)) {
            return true;
        }
        if ($nextOnOpensAt->lte($targetOffAt)) {
            return true;
        }

        return false;
    }

    protected function reservationsForZoneEndedWithin(LitecomZone $zone, CarbonInterface $from, CarbonInterface $to)
    {
        return Reservation::query()
            ->whereNull('canceled_at')
            ->whereBetween('end_time', [$from, $to])
            ->whereIn('court_id', $zone->courts_ids)
            ->orderBy('end_time')
            ->get();
    }


    protected function setScene(LitecomZone $zone, int $scene, bool $dryRun): bool
    {
        if ($dryRun) {
            logger()->info('DRY-RUN: setScene', [
                'zone_id' => $zone->zone_id,
                'connection' => $zone->connection,
                'scene' => $scene,
            ]);
            return true;
        }

        try {
            if ($scene === 0) {
                $this->litecomService->off($zone);
            } else {
                $this->litecomService->on($zone, $scene, null);
            }
            return true;
        } catch (\Throwable $e) {
            logger()->error('Litecom setScene FAIL: ' . $e->getMessage(), [
                'zone_id' => $zone->zone_id,
                'connection' => $zone->connection,
                'scene' => $scene,
            ]);
            return false;
        }
    }
}
