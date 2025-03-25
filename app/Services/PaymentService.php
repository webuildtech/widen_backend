<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Jobs\CheckRefundSlots;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Reservation;
use App\Models\User;
use LucasDotVin\Soulbscription\Models\FeatureConsumption;

class PaymentService
{
    public function createFromPlan(Plan $plan, User $user): Payment
    {
        $priceDetails = applyDiscountAndCalculatePriceDetails($plan->price, $user->discount_on_everything);

        $paidAmountFromBalance = $user->getDeductedAmount($priceDetails->priceWithVat);
        $user->deductBalance($paidAmountFromBalance);

        $payment = $plan->payment()->create([
            'user_id' => $user->id,
            'price' => $priceDetails->price,
            'vat' => $priceDetails->vat,
            'discount' => $priceDetails->discount,
            'price_with_vat' => $priceDetails->priceWithVat,
            'paid_amount' => $priceDetails->priceWithVat - $paidAmountFromBalance,
            'paid_amount_from_balance' => $paidAmountFromBalance
        ]);

        return $payment;
    }

    public function createFromReservation(Reservation $reservation, User $user = null): Payment
    {
        $paidAmountFromBalance = 0;

        if ($user) {
            $paidAmountFromBalance = $user->getDeductedAmount($reservation->price);
            $user->deductBalance($paidAmountFromBalance);
        }

        $payment = $reservation->payment()->create([
            'user_id' => $reservation->user_id,
            'discount' => $reservation->discount,
            'price' => $reservation->price - $reservation->vat,
            'vat' => $reservation->vat,
            'price_with_vat' => $reservation->price,
            'paid_amount' => $reservation->price - $paidAmountFromBalance,
            'paid_amount_from_balance' => $paidAmountFromBalance
        ]);

        return $payment;
    }

    public function createFromAmount(float $amount, User $user): Payment
    {
        $vat = round($amount - ($amount / 1.21), 2);

        $payment = Payment::create([
            'user_id' => $user->id,
            'price' => $amount - $vat,
            'vat' => $vat,
            'price_with_vat' => $amount,
            'paid_amount' => $amount,
        ]);

        return $payment;
    }

    public function approve(Payment $payment): Payment
    {
        if ($payment->status === PaymentStatus::PAID->value) {
            return $payment;
        }

        $payment->update(['status' => PaymentStatus::PAID->value, 'paid_at' => now()]);

        match ($payment->paymentable_type) {
            'plan' => $this->handlePlanPayment($payment),
            'reservation' => $this->handleReservationPayment($payment),
            default => $this->handleDefaultPayment($payment),
        };

        return $payment;
    }

    public function cancel(Payment $payment, PaymentStatus $status): Payment
    {
        if ($payment->status !== PaymentStatus::PENDING->value) {
            return $payment;
        }

        $payment->update(['status' => $status->value]);

        $payment->user?->addBalance($payment->paid_amount_from_balance);

        if ($payment->paymentable_type === 'reservation') {
            $reservation = $payment->paymentable;

            if ($reservation->feature_consumption_id) {
               FeatureConsumption::where('id', $reservation->feature_consumption_id)->delete();
            }

            $reservation->slots()->delete();
            $reservation->times()->delete();
            $reservation->delete();
        }

        return $payment;
    }

    private function handlePlanPayment(Payment $payment): void
    {
        $payment->user->subscribeTo($payment->paymentable);
    }

    private function handleReservationPayment(Payment $payment): void
    {
        $payment->paymentable->update(['is_paid' => true]);

        CheckRefundSlots::dispatch($payment->paymentable);
    }

    private function handleDefaultPayment(Payment $payment): void
    {
        $payment->user->addBalance($payment->paid_amount);
    }
}
