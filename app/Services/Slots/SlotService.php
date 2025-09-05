<?php

namespace App\Services\Slots;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class SlotService
{
    public function generate(string $startTime, string $endTime, int $intervalMinutes = 30): Collection
    {
        $slots = collect();

        $period = CarbonPeriod::create(
            Carbon::createFromTimeString($startTime),
            "$intervalMinutes minutes",
            Carbon::createFromTimeString($endTime)
        );

        foreach ($period as $time) {
            $end = $time->copy()->addMinutes($intervalMinutes)->format('H:i');

            $slots->push([
                'start_time' => $time->format('H:i'),
                'end_time' => $end === '00:00' ? '24:00' : $end,
            ]);
        }

        $slots->pop();

        return $slots;
    }
}
