<?php

namespace App\Services\Payments;

use App\Enums\PaymentStatus;
use App\Jobs\GenerateInvoice;
use App\Models\Guest;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Reservation;
use App\Models\ReservationGroup;
use App\Models\User;

class PaymentService
{
    public function __construct(
        protected PaymentHandlerResolver $paymentHandlerResolver
    ) {}

    public function createFromPlan(Plan $plan, User $user, bool $renew = false): Payment
    {
        $priceDetails = applyDiscountAndCalculatePriceDetails($plan->price, $user->discount_on_everything);

        $paidAmountFromBalance = $user->getDeductedAmount($priceDetails->price_with_vat);
        $user->deductBalance($paidAmountFromBalance);

        $payment = new Payment([
            'renew' => $renew,
            'price' => $priceDetails->price,
            'vat' => $priceDetails->vat,
            'discount' => $priceDetails->discount,
            'price_with_vat' => $priceDetails->price_with_vat,
            'paid_amount' => $priceDetails->price_with_vat - $paidAmountFromBalance,
            'paid_amount_from_balance' => $paidAmountFromBalance
        ]);

        $payment->paymentable()->associate($plan);
        $user->payments()->save($payment);

        return $payment;
    }

    public function createFromReservationGroup(ReservationGroup $reservationGroup, User|Guest $owner, bool $allowMinus = false): Payment
    {
        $reservations = $reservationGroup->reservations();

        $discount = $reservations->sum('discount');
        $price = $reservations->sum('price');
        $vat = $reservations->sum('vat');
        $priceWithVat = $reservations->sum('price_with_vat');

        $paidAmountFromBalance = 0;

        if ($owner instanceof User) {
            $paidAmountFromBalance = $allowMinus ? $priceWithVat : $owner->getDeductedAmount($priceWithVat);
            $owner->deductBalance($paidAmountFromBalance);
        }

        $payment = new Payment([
            'discount' => $discount,
            'price' => $price,
            'vat' => $vat,
            'price_with_vat' => $priceWithVat,
            'paid_amount' => $priceWithVat - $paidAmountFromBalance,
            'paid_amount_from_balance' => $paidAmountFromBalance,
        ]);

        $payment->paymentable()->associate($reservationGroup);
        $owner->payments()->save($payment);

        return $payment;
    }

    public function createFromAmount(float $amount, User $user): Payment
    {
        $vat = round($amount - ($amount / 1.21), 2);

        return $user->payments()->create([
            'price' => $amount - $vat,
            'vat' => $vat,
            'price_with_vat' => $amount,
            'paid_amount' => $amount,
        ]);
    }

    public function approve(Payment $payment): Payment
    {
        if ($payment->status === PaymentStatus::PAID) {
            return $payment;
        }

        $payment->update(['status' => PaymentStatus::PAID->value, 'paid_at' => now()]);

        $this->paymentHandlerResolver->handle($payment);

        GenerateInvoice::dispatch($payment);

        return $payment;
    }

    public function cancel(Payment $payment, PaymentStatus $status): Payment
    {
        if ($payment->status !== PaymentStatus::PENDING) {
            return $payment;
        }

        $payment->update(['status' => $status->value]);

        if ($payment->owner instanceof User) {
            $payment->owner->addBalance($payment->paid_amount_from_balance);
        }

        if ($payment->paymentable_type === 'reservationGroup') {
            $reservationGroup = $payment->paymentable;

            $reservationGroup->reservations->each(function (Reservation $reservation) {
                if ($reservation->delete_after_failed_payment) {
                    $reservation->slots()->delete();
                    $reservation->delete();
                } elseif (!$reservation->is_paid) {
                    $reservation->update(['reservation_group_id' => null]);
                }
            });

            $reservationGroup->delete();
        }

        return $payment;
    }
}
