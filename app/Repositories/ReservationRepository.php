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
            ->get(['slot_start', 'slot_end'])
            ->mapWithKeys(fn ($slot) => [$slot->slot_start->format('H:i') => $slot->slot_end->format('H:i')])
            ->toArray();
    }

    public function getUnpaidForDate(Carbon $date, string $operator = '='): Collection
    {
        return Reservation::whereDeleteAfterFailedPayment(false)
            ->whereIsPaid(false)
            ->whereCanceledAt(null)
            ->whereOwnerType('user')
            ->where('start_time', $operator, $date)
            ->get();
    }
}
