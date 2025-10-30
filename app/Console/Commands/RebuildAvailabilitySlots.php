<?php

namespace App\Console\Commands;

use App\Models\AvailabilitySlot;
use App\Models\Court;
use App\Models\Interval;
use App\Services\Slots\SlotService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class RebuildAvailabilitySlots extends Command
{
    protected $signature = 'app:availability:rebuild {--now=}';

    protected $description = 'Regenerates availability slots for courts';

    /** @var array<int, array<string, Collection>> (interval_id x weekday) */
    protected array $slotCache = [];

    public function handle(SlotService $slotService)
    {
        $nowOpt = $this->option('now');

        if ($nowOpt) {
            try {
                $now = Carbon::parse($nowOpt);
            } catch (\Throwable $e) {
                $this->error("Neteisingas --now formatas: '{$nowOpt}'. Pvz.: 2025-10-30 00:05");
                return self::FAILURE;
            }
        } else {
            $now = now();
        }

        $graceMinutes = 30;

        $timeWindowStart = $now->copy()->subMinutes($graceMinutes);
        $genFromDate = $now->copy()->startOfDay();
        $deleteFromDate = $now->toDateString();

        $shouldRefreshTail = $timeWindowStart->toDateString() !== $now->toDateString();
        $tailDate = $now->copy()->subDay()->toDateString();
        $tailStartTimeStr = $timeWindowStart->format('H:i');

        Court::query()
            ->select(['id', 'court_type_id'])
            ->withMax('intervals as max_date_to', 'date_to')
            ->with([
                'intervals' => fn($q) => $q->select(['intervals.id', 'intervals.date_from', 'intervals.date_to'])->withPivot('order'),
                'intervals.prices' => fn($q) => $q->select(['interval_id', 'day', 'start_time', 'end_time']),
            ])
            ->chunkById(100, function ($courts) use ($slotService, $genFromDate, $deleteFromDate, $timeWindowStart, $shouldRefreshTail, $tailDate, $tailStartTimeStr) {
                foreach ($courts as $court) {
                    if ($shouldRefreshTail) {
                        $this->refreshPreviousDayTail($court, $tailDate, $tailStartTimeStr, $timeWindowStart, $slotService);
                    }

                    AvailabilitySlot::query()
                        ->where('court_id', $court->id)
                        ->where('date', '>=', $deleteFromDate)
                        ->delete();

                    if (!$court->max_date_to) {
                        continue;
                    }

                    $periodEnd = Carbon::parse($court->max_date_to)->endOfDay();
                    if ($periodEnd->lt($genFromDate)) {
                        continue;
                    }

                    $reservationsByDate = $court->reservationSlots()
                        ->active()
                        ->whereBetween('slot_start', [$timeWindowStart, $periodEnd])
                        ->get(['slot_start', 'slot_end'])
                        ->groupBy(fn($r) => $r->slot_start->toDateString())
                        ->map(fn(Collection $group) => $group->mapWithKeys(
                            fn($r) => [$r->slot_start->format('H:i') => $r->slot_end->format('H:i')]
                        ));

                    $downtimes = $court->downtimes()
                        ->where('date_to', '>=', $genFromDate->toDateString())
                        ->where('date_from', '<=', $periodEnd->toDateString())
                        ->get(['date_from', 'date_to', 'start_time', 'end_time']);

                    $intervals = $court->intervals->sortBy(fn($i) => $i->pivot->order)->values();
                    foreach ($intervals as $interval) {
                        $this->warmSlotCache($interval, $slotService);
                    }

                    $blockedByDate = $this->buildBlockedByDate($downtimes, $genFromDate, $periodEnd, $slotService);

                    $period = CarbonPeriod::create($genFromDate, '1 day', $periodEnd);

                    $bulk = [];
                    foreach ($period as $date) {
                        $dayKey = $date->toDateString();
                        $weekday = strtolower($date->format('D'));

                        $interval = $this->pickIntervalForDay($intervals, $date);
                        if (!$interval) {
                            continue;
                        }

                        $daySlots = $this->slotCache[$interval->id][$weekday] ?? collect();
                        if ($daySlots->isEmpty()) {
                            continue;
                        }

                        $reservedMap = $reservationsByDate[$dayKey] ?? collect();
                        $blockedMap  = $blockedByDate[$dayKey] ?? collect();

                        foreach ($daySlots as $slot) {
                            $bulk[] = [
                                'court_id' => $court->id,
                                'court_type_id' => $court->court_type_id,
                                'date' => $dayKey,
                                'day' => $weekday,
                                'start_time' => $slot['start_time'],
                                'end_time' => $slot['end_time'],
                                'is_reserved' => $reservedMap->has($slot['start_time']),
                                'is_blocked' => $blockedMap->has($slot['start_time']),
                            ];
                        }

                        if (count($bulk) >= 5000) {
                            $this->flushBulk($bulk);
                            $bulk = [];
                        }
                    }

                    if (!empty($bulk)) {
                        $this->flushBulk($bulk);
                    }
                }
            });

        return self::SUCCESS;
    }

    protected function refreshPreviousDayTail(Court $court, string $tailDate, string $tailStartTimeStr, Carbon $timeWindowStart, SlotService $slotService): void
    {
        $existing = AvailabilitySlot::query()
            ->where('court_id', $court->id)
            ->where('date', $tailDate)
            ->where('start_time', '>=', $tailStartTimeStr)
            ->get(['date', 'start_time', 'end_time']);

        if ($existing->isEmpty()) {
            return;
        }

        $yesterdayStart = Carbon::parse($tailDate)->startOfDay();
        $yesterdayEnd = Carbon::parse($tailDate)->endOfDay();

        $reservationsByDate = $court->reservationSlots()
            ->active()
            ->whereBetween('slot_start', [$timeWindowStart, $yesterdayEnd])
            ->get(['slot_start', 'slot_end'])
            ->groupBy(fn($r) => $r->slot_start->toDateString())
            ->map(fn(Collection $group) => $group->mapWithKeys(
                fn($r) => [$r->slot_start->format('H:i') => $r->slot_end->format('H:i')]
            ));

        $downtimes = $court->downtimes()
            ->where('date_to', '>=', $tailDate)
            ->where('date_from', '<=', $tailDate)
            ->get(['date_from', 'date_to', 'start_time', 'end_time']);

        $blockedByDate = $this->buildBlockedByDate($downtimes, $yesterdayStart, $yesterdayEnd, $slotService);

        $reservedMap = $reservationsByDate[$tailDate] ?? collect();
        $blockedMap = $blockedByDate[$tailDate] ?? collect();

        $updates = [];

        foreach ($existing as $row) {
            $start = $row->start_time;
            $updates[] = [
                'court_id' => $court->id,
                'court_type_id' => $court->court_type_id,
                'date' => $tailDate,
                'start_time' => $start,
                'end_time' => $row->end_time,
                'is_reserved' => $reservedMap->has($start),
                'is_blocked' => $blockedMap->has($start),
            ];
        }

        AvailabilitySlot::upsert(
            $updates,
            ['court_id', 'date', 'start_time'],
            ['is_reserved', 'is_blocked']
        );
    }

    protected function pickIntervalForDay($intervals, Carbon $date): ?Interval
    {
        $day = $date->toDateString();

        return $intervals->first(function ($i) use ($day) {
            $from = $i->date_from->toDateString();
            $to = $i->date_to->toDateString();

            return ($from <= $day) && ($day <= $to);
        });
    }

    protected function warmSlotCache(Interval $interval, SlotService $slotService): void
    {
        if (isset($this->slotCache[$interval->id])) {
            return;
        }

        $this->slotCache[$interval->id] = [];

        foreach ($interval->prices as $price) {
            $dayKey = strtolower($price->day);

            $generated = $slotService->generate($price->start_time, $price->end_time)->all();

            $this->slotCache[$interval->id][$dayKey] = ($this->slotCache[$interval->id][$dayKey] ?? collect())->concat($generated);
        }
    }

    protected function buildBlockedByDate($downtimes, Carbon $from, Carbon $to, SlotService $slotService): array
    {
        $result = [];

        $periodFrom = $from->toDateString();
        $periodTo = $to->toDateString();

        $timeRangeCache = [];

        foreach ($downtimes as $downtime) {
            $downtimeFrom = Carbon::parse($downtime->date_from)->toDateString();
            $downtimeTo = Carbon::parse($downtime->date_to)->toDateString();

            if ($downtimeTo < $periodFrom || $downtimeFrom > $periodTo) {
                continue;
            }

            $useFrom = max($downtimeFrom, $periodFrom);
            $useTo = min($downtimeTo, $periodTo);

            if ($useFrom > $useTo) {
                continue;
            }

            $rangeKey = "{$downtime->start_time}-{$downtime->end_time}";
            if (!isset($timeRangeCache[$rangeKey])) {
                $timeRangeCache[$rangeKey] = $slotService->generate($downtime->start_time, $downtime->end_time)
                    ->mapWithKeys(fn($s) => [$s['start_time'] => $s['end_time']]);
            }

            $genMap = $timeRangeCache[$rangeKey];

            $period = CarbonPeriod::create($useFrom, '1 day', $useTo);
            foreach ($period as $day) {
                $dayKey = $day->toDateString();

                $result[$dayKey] = $result[$dayKey] ?? collect();

                foreach ($genMap as $start => $end) {
                    if (!$result[$dayKey]->has($start)) {
                        $result[$dayKey]->put($start, $end);
                    }
                }
            }
        }
        return $result;
    }

    protected function flushBulk(array $rows): void
    {
        AvailabilitySlot::query()->insertOrIgnore($rows);
    }
}

