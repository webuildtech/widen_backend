<?php

namespace App\Observers;

use App\Models\ReservationTime;

class ReservationTimeObserver
{
    public function created(ReservationTime $reservationTime): void
    {
        $reservationTime->reservation->updateTotalPrice();
    }

    public function updated(ReservationTime $reservationTime): void
    {
        if ($reservationTime->isDirty('canceled_at')) {
            $reservationTime->reservation->updateTotalPrice();
        }
    }

    public function deleted(ReservationTime $reservationTime): void
    {
        $reservationTime->reservation->updateTotalPrice();
    }

    /**
     * Handle the ReservationTime "restored" event.
     */
    public function restored(ReservationTime $reservationTime): void
    {
        //
    }

    /**
     * Handle the ReservationTime "force deleted" event.
     */
    public function forceDeleted(ReservationTime $reservationTime): void
    {
        //
    }
}
