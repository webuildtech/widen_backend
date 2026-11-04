<?php

namespace App\Repositories;

use App\Models\Court;
use App\Models\Interval;
use Carbon\Carbon;

class IntervalRepository
{
    public function getForCourtAndDateFirst(Court $court, Carbon $date): ?Interval
    {
        return $court->intervals()
            ->whereDate('date_from', '<=', $date)
            ->whereDate('date_to', '>=', $date)
            ->orderByPivot('order')
            ->first();
    }

    public function getUnderlyingForCourt(Court $court, Interval $interval): ?Interval
    {
        return $court->intervals()
            ->whereKeyNot($interval->id)
            ->whereDate('date_from', '<=', $interval->date_from)
            ->whereDate('date_to', '>=', $interval->date_to)
            ->orderByPivot('order')
            ->first();
    }
}
