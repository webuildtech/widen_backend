<?php

namespace App\Repositories;

use App\Models\Court;
use Carbon\Carbon;

class ReservationRepository
{
    public function getReservedSlotsForCourtAndDate(Court $court, Carbon $date): array
    {
        return $court->reservationSlots()
            ->active()
            ->whereDate('slot_start', $date)
            ->pluck('slot_end', 'slot_start')
            ->toArray();
    }
}
