<?php

namespace App\Services\Litecom;

use App\Models\LitecomZone;
use App\Models\Reservation;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

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

        $windowTolerance = 1;

        $nowAligned = $now->copy()->second(0)->microsecond(0);

        $zones = LitecomZone::with('courts')->get();

        foreach ($zones as $zone) {
            if ($zone->manual_override_until && $zone->manual_override_until->isFuture()) {
                $skipped++;
                continue;
            }

            $inOnWindow = $this->isInAutoOnWindow($zone, $nowAligned);
            $hasActive = $this->hasActiveReservationNow($zone, $nowAligned);

            $clearedExpiredOverride = false;
            if ($zone->manual_override_until && $zone->manual_override_until->isPast()) {
                $zone->forceFill([
                    'manual_override_until' => null,
                    'manual_override_source' => null,
                ])->saveQuietly();
                $clearedExpiredOverride = true;

                if (!$hasActive && !$inOnWindow && $zone->active_scene !== 0) {
                    $inWait = $this->endedWithinWait($zone, $nowAligned, $zone->auto_turn_off_after);

                    if (!$inWait) {
                        $ok = $this->setScene($zone, 0, $dryRun);
                        $ok ? $turnedOff++ : $skipped++;
                        continue;
                    }
                }
            }

            if ($hasActive || $inOnWindow) {
                if ($zone->active_scene !== $zone->auto_scene) {
                    $ok = $this->setScene($zone, $zone->auto_scene, $dryRun);
                    $ok ? $turnedOn++ : $skipped++;
                } else {
                    $skipped++;
                }

                continue;
            }

            $endFrom = $nowAligned->copy()->subMinutes($zone->auto_turn_off_after + $windowTolerance);
            $endTo = $nowAligned->copy()->subMinutes($zone->auto_turn_off_after);

            if ($this->endedWithinWindowExists($zone, $endFrom, $endTo)) {
                if ($zone->active_scene === 0) {
                    $skipped++;
                } else {
                    $ok = $this->setScene($zone, 0, $dryRun);
                    $ok ? $turnedOff++ : $skipped++;
                }
                continue;
            }

            if (!$clearedExpiredOverride && $zone->active_scene !== 0 && !$this->endedWithinWait($zone, $nowAligned, $zone->auto_turn_off_after)) {
                $zone->forceFill([
                    'manual_override_until' => $nowAligned->copy()->addHours(2),
                    'manual_override_source' => 'device',
                ])->saveQuietly();

                $skipped++;
                continue;
            }

            $skipped++;
        }

        return compact('turnedOn', 'turnedOff', 'skipped');
    }

    protected function reservations(LitecomZone $zone): Builder
    {
        return Reservation::query()->whereNull('canceled_at')->whereIn('court_id', $zone->courts_ids);
    }

    protected function isInAutoOnWindow(LitecomZone $zone, CarbonInterface $now): bool
    {
        return $this->reservations($zone)
            ->whereBetween('start_time', [$now, $now->copy()->addMinutes($zone->auto_turn_on_before)])
            ->exists();
    }

    protected function hasActiveReservationNow(LitecomZone $zone, CarbonInterface $now): bool
    {
        return $this->reservations($zone)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->exists();
    }

    protected function endedWithinWindowExists(LitecomZone $zone, CarbonInterface $from, CarbonInterface $to): bool
    {
        return $this->reservations($zone)
            ->whereBetween('end_time', [$from, $to])
            ->exists();
    }

    protected function endedWithinWait(LitecomZone $zone, CarbonInterface $now, int $waitMinutes): bool
    {
        $from = $now->copy()->subMinutes($waitMinutes);

        return $this->reservations($zone)
            ->where('end_time', '>', $from)
            ->where('end_time', '<=', $now)
            ->exists();
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
