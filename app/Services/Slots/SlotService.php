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
            $slots->push([
                'start_time' => $time->format('H:i'),
                'end_time' => $time->copy()->addMinutes($intervalMinutes)->format('H:i'),
            ]);
        }

        return $slots;
    }
}
