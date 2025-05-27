<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Models\ReservationSlot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use LucasDotVin\Soulbscription\Models\FeatureConsumption;

class CheckRefundSlots implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Reservation $reservation
    ) {}

    public function handle(): void
    {
        $this->reservation->slots->each(function (ReservationSlot $slot) {
            $refundSlot = ReservationSlot::whereCourtId($slot->court_id)
                ->whereSlotStart($slot->slot_start)
                ->whereSlotEnd($slot->slot_end)
                ->whereTrySell(true)
                ->where('id', '!=', $slot->id)
                ->first();

            if ($refundSlot) {
                $refundSlot->update(['is_refunded' => true, 'try_sell' => false]);

                $reservationTime = $refundSlot->reservationTime;

                $reservationTime->update(['refunded_amount' => $reservationTime->refunded_amount + $refundSlot->price_with_vat]);

                $reservationTime->reservation->user->addBalance($refundSlot->price_with_vat);

                if (!$reservationTime->slots()->where('is_refunded', true)->exists()) {
                    $reservationTime->slots()->delete();
                }
            }
        });
    }
}
