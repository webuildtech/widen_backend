<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Models\ReservationGroup;
use App\Models\ReservationSlot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckRefundSlots implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ReservationGroup $reservationGroup
    )
    {
        $this->reservationGroup->load('reservations', 'reservations.slots');
    }

    public function handle(): void
    {
        $this->reservationGroup->reservations->each(function (Reservation $reservation) {
            $reservation->slots->each(function (ReservationSlot $slot) use ($reservation) {
                $refundSlot = ReservationSlot::whereCourtId($slot->court_id)
                    ->whereSlotStart($slot->slot_start)
                    ->whereSlotEnd($slot->slot_end)
                    ->whereTrySell(true)
                    ->where('id', '!=', $slot->id)
                    ->first();

                if ($refundSlot) {
                    $refundSlot->update(['is_refunded' => true, 'try_sell' => false]);

                    $reservation->increment('refunded_amount', $refundSlot->price_with_vat);

                    $reservation->owner->addBalance($refundSlot->price_with_vat);

                    if (!$reservation->slots()->where('is_refunded', true)->exists()) {
                        $reservation->slots()->delete();
                    }
                }
            });
        });
    }
}
