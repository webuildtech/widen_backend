<?php

namespace App\Repositories;

use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DowntimeRepository
{
    public function getForCourtAndDate(Court $court, Carbon $date): Collection
    {
        return $court->downtimes()
            ->whereDate('date_from', '<=', $date)
            ->whereDate('date_to', '>=', $date)
            ->get();
    }
}
