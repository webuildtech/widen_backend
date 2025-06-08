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
}
