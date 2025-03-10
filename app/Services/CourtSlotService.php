<?php

namespace App\Services;

use App\Models\Court;
use App\Models\IntervalPrice;
use App\Models\ReservationSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CourtSlotService
{
    public function generateFreeSlots(Court $court, Carbon $date): array
    {
        $interval = $court->intervals()
            ->whereDate('date_from', '<=', $date)
            ->whereDate('date_to', '>=', $date)
            ->orderByPivot('order')
            ->first();

        if (!$interval) {
            return [];
        }

        $prices = $this->filterPricesByDate($interval, $date);
        $reservedSlots = $this->getReservedSlots($date, $court);

        return $this->calculateAvailableSlots($prices, $court, $date, $reservedSlots);
    }

    private function filterPricesByDate($interval, Carbon $date): Collection
    {
        $query = $interval->prices()->where('day', strtolower($date->format('D')));

        if ($date->isToday()) {
            $query->where('end_time', '>=', now()->format('H:i'));
        }

        return $query->get();
    }

    private function getReservedSlots(Carbon $date, Court $court): array
    {
        return ReservationSlot::whereDate('slot_start', $date)
            ->where('court_id', $court->id)
            ->where('try_sell', false)
            ->where('is_refunded', false)
            ->pluck('slot_end', 'slot_start')
            ->toArray();
    }

    private function calculateAvailableSlots(Collection $prices, Court $court, Carbon $date, array $reservedSlots): array
    {
        return $prices->flatMap(function (IntervalPrice $price) use ($court, $date, $reservedSlots) {
            $startTime = Carbon::parse("{$date->format('Y-m-d')} {$price->start_time}");
            $endTime = Carbon::parse("{$date->format('Y-m-d')} {$price->end_time}");

            return $this->generateTimeSlots($startTime, $endTime, $price, $court, $reservedSlots);
        })->toArray();
    }

    private function generateTimeSlots(Carbon $startTime, Carbon $endTime, IntervalPrice $price, Court $court, array $reservedSlots): Collection
    {
        $slots = collect();

        while ($startTime < $endTime) {
            $slotEnd = $startTime->copy()->addMinutes(30);

            if ($startTime->gt(now()) && !isset($reservedSlots[$startTime->format('Y-m-d H:i:s')])) {
                $slots->push([
                    'court_id' => $court->id,
                    'date' => $startTime->format('Y-m-d'),
                    'day' => $price->day,
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                    'price' => floatval($price->price),
                ]);
            }

            $startTime = $slotEnd;
        }

        return $slots;
    }

    public function getBestSlots(Court $court, Carbon $date): array
    {
        $allSlots = $this->generateFreeSlots($court, $date);

        if (count($allSlots) < 3) {
            return $allSlots;
        }

        return $this->mergeAdjacentSlots($allSlots);
    }

    private function mergeAdjacentSlots(array $slots): array
    {
        $mergedSlots = [];
        $i = 0;

        while ($i < count($slots) && count($mergedSlots) < 3) {
            $start = Carbon::parse($slots[$i]['start_time']);
            $end = Carbon::parse($slots[$i]['end_time']);

            if ($i + 1 < count($slots)) {
                $nextStart = Carbon::parse($slots[$i + 1]['start_time']);
                $nextEnd = Carbon::parse($slots[$i + 1]['end_time']);

                if ($end->equalTo($nextStart)) {
                    $mergedSlots[] = [
                        'court_id' => $slots[$i]['court_id'],
                        'date' => $slots[$i]['date'],
                        'day' => $slots[$i]['day'],
                        'start_time' => $start->format('H:i'),
                        'end_time' => $nextEnd->format('H:i'),
                        'price' => $slots[$i]['price'] + $slots[$i + 1]['price'],
                    ];
                    $i += 2;
                    continue;
                }
            }

            $mergedSlots[] = $slots[$i];
            $i++;
        }

        return $mergedSlots;
    }
}

