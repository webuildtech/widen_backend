<?php

namespace App\Services\Reservations;

use App\Enums\DiscountCodeType;
use App\Models\DiscountCode;
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

    public function createWithSlots(Guest|User $owner, array $reservationData, Collection $slots, DiscountCode $discountCode = null): Reservation
    {
        $reservation = $owner->reservations()->create($reservationData);

        foreach ($slots as $slot) {
            $price = $slot['price'];
            $totalDiscount = 0;

            if ($owner->discount_on_everything > 0) {
                $discount = round($price * ($owner->discount_on_everything / 100), 2);

                $price -= $discount;
                $totalDiscount += $discount;
            }

            if ($discountCode) {
                $discount = 0;

                if ($discountCode->type === DiscountCodeType::FIXED) {
                    $discount = min($discountCode->value, $price);
                } elseif ($discountCode->type === DiscountCodeType::PERCENT) {
                    $discount = round($price * ($discountCode->value / 100), 2);
                }

                $price -= $discount;
                $totalDiscount += $discount;
            }

            $price = applyDiscountAndCalculatePriceDetails($price);

            $reservation->slots()->create([
                'slot_start' => Carbon::parse($slot['date'] . ' ' . $slot['start_time']),
                'slot_end' => Carbon::parse($slot['date'] . ' ' . $slot['end_time']),
                'court_id' => $slot['court_id'],
                'price' => $price->price,
                'vat' => $price->vat,
                'discount' => $totalDiscount,
                'price_with_vat' => $price->price_with_vat,
            ]);

            $reservation->price += $price->price;
            $reservation->vat += $price->vat;
            $reservation->discount += $totalDiscount;
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

        if (!$reservation->is_paid) {
            $reservation->update(['canceled_at' => now()]);
            $reservation->slots()->delete();

            return ['balance' => $user->balance];
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

    public function cancel(Reservation $reservation)
    {
        if (!$reservation->canceled_at) {
            $data = ['canceled_at' => now()];

            if ($reservation->is_paid && $reservation->owner instanceof User) {
                $reservation->owner->addBalance($reservation->price_with_vat);
                $data['refunded_amount'] = $reservation->price_with_vat;
            }

            $reservation->update($data);
            $reservation->slots()->delete();
        }
    }

    public function delete(Reservation $reservation)
    {
        if ($reservation->is_paid && $reservation->owner instanceof User) {
            $reservation->owner->addBalance($reservation->price_with_vat);
        }

        $reservation->slots()->delete();
        $reservation->delete();
    }

    public function bulkAction(Collection $reservations, string $action)
    {
        foreach ($reservations as $reservation) {
            if ($action === 'cancel') {
                $this->cancel($reservation);
            } elseif ($action === 'delete') {
                $this->delete($reservation);
            }
        }
    }
}
