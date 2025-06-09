<?php

namespace App\Repositories;

use App\Models\Court;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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

    public function getUnpaidForDate(Carbon $date, string $operator = '='): Collection
    {
        return Reservation::whereDeleteAfterFailedPayment(false)
            ->whereIsPaid(false)
            ->whereOwnerType('user')
            ->where('start_time', $operator, $date)
            ->get();
    }
}
