<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Models\ReservationGroup;
use App\Services\Reservations\ReservationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckRefundSlots implements ShouldQueue
{
    use Queueable;

    protected ReservationService $reservationService;

    public function __construct(
        public ReservationGroup $reservationGroup
    )
    {
        $this->reservationGroup->load('reservations', 'reservations.slots');
        $this->reservationService = new ReservationService();
    }

    public function handle(): void
    {
        $this->reservationGroup->reservations->each(function (Reservation $reservation) {
            $this->reservationService->refundSlots($reservation);
        });
    }
}
