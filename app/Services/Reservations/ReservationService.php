<?php

namespace App\Services\Reservations;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use App\Models\User;
use App\Services\PlanCourtTypeRuleService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    public function __construct(
        protected PlanCourtTypeRuleService $planCourtTypeRuleService,
    )
    {
    }

    public function createWithSlots(Guest|User $owner, array $reservationData, Collection $slots): Reservation
    {
        $reservation = $owner->reservations()->create($reservationData);

        foreach ($slots as $slot) {
            $price = applyDiscountAndCalculatePriceDetails($slot['price'], $owner->discount_on_everything ?? 0);

            $reservation->slots()->create([
                'slot_start' => Carbon::parse($slot['date'] . ' ' . $slot['start_time']),
                'slot_end' => Carbon::parse($slot['date'] . ' ' . $slot['end_time']),
                'court_id' => $slot['court_id'],
                'price' => $price->price,
                'vat' => $price->vat,
                'discount' => $price->discount,
                'price_with_vat' => $price->price_with_vat,
            ]);

            $reservation->price += $price->price;
            $reservation->vat += $price->vat;
            $reservation->discount += $price->discount;
            $reservation->price_with_vat += $price->price_with_vat;
        }

        $reservation->save();

        return $reservation;
    }

    public function cancelByUser(User $user, Reservation $reservation): array
    {
        if ($reservation->canceled_at) {
            throw ValidationException::withMessages(['error' => 'Veiksmas negalimas!']);
        }

        $now = now();
        $cancelBefore = $now->copy()->addHours($this->planCourtTypeRuleService->getCancelHoursBefore($user, $reservation->court->court_type_id));

        if ($reservation->start_time->isBefore($cancelBefore)) {
            $reservation->update(['canceled_at' => $now]);
            $reservation->slots()->update(['try_sell' => true]);

            return ['balance' => $user->balance];
        }

        $user->addBalance($reservation->price_with_vat);

        $reservation->update([
            'refunded_amount' => $reservation->price_with_vat,
            'canceled_at' => $now,
        ]);

        $reservation->slots()->delete();

        return ['balance' => $user->balance];
    }

    public function refundSlots(Reservation $reservation): void
    {
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
    }
}
