<?php

namespace App\Jobs;

use App\Models\ReservationGroup;
use App\Services\Reservations\ReservationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckRefundSlots implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int                   $reservationGroupId,
        protected ReservationService $reservationService
    )
    {
    }

    public function handle(): void
    {
        $reservationGroup = ReservationGroup::with('reservations.slots')->findOrFail($this->reservationGroupId);

        $reservationGroup->reservations->each(fn($r) => $this->reservationService->refundSlots($r));
    }
}
