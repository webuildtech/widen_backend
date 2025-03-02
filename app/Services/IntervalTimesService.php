<?php

namespace App\Services;

use App\Models\Court;
use App\Models\Interval;
use App\Models\IntervalPrice;
use App\Models\ReservationSlot;
use Carbon\Carbon;

class IntervalTimesService
{
    public function getIntervalTimes(Court $court, Carbon $date): array
    {
        $interval = $court->intervals()
            ->whereDate('date_from', '<=', $date)
            ->whereDate('date_to', '>=', $date)
            ->orderByPivot('order')
            ->first();

        if (!$interval) {
            return [];
        }

        $isToday = $date->isToday();

        $prices = $interval->prices()->where('day', strtolower($date->format('D')));

        $isToday && $prices->where('end_time', '>=', now()->format('H:i'));

        $prices = $prices->get();

        $reservedSlots = ReservationSlot::whereDate('slot_start', $date)
            ->where('try_sell', false)
            ->where('is_refunded', false)
            ->pluck('slot_end', 'slot_start')
            ->toArray();

        $times = [];

        $prices->each(function (IntervalPrice $price) use ($court, $isToday, &$times, $reservedSlots, $date) {
            $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $price->start_time);
            $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $price->end_time);

            $currentTime = $startTime->copy();

            while ($currentTime < $endTime) {
                if (!$isToday || $currentTime->gt(now())) {
                    if (!isset($reservedSlots[$currentTime->format('Y-m-d H:i:s')])) {
                        $times[] = [
                            'court_id' => $court->id,
                            "date" => $date->format('Y-m-d'),
                            "day" => $price->day,
                            "start_time" => $currentTime->format('H:i'),
                            "end_time" => $currentTime->copy()->addMinutes(30)->format('H:i'),
                            "price" => floatval($price->price)
                        ];
                    }
                }

                $currentTime->addMinutes(30);
            }
        });

        return $times;
    }
}
