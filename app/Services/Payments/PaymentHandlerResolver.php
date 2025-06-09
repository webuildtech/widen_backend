<?php

namespace App\Services\Payments;

use App\Jobs\CheckRefundSlots;
use App\Models\Payment;

class PaymentHandlerResolver
{
    public function handle(Payment $payment): void
    {
        match ($payment->paymentable_type) {
            'plan' => $this->handlePlan($payment),
            'reservationGroup' => $this->handleReservationGroup($payment),
            default => $this->handleDefault($payment)
        };
    }

    private function handlePlan(Payment $payment): void
    {
        $user = $payment->owner;

        $subscription = $user->subscription ?? $user->lastSubscription();

        if (!$subscription || ($subscription->is_overdue && $subscription->canceled_at)) {
            $user->subscribeTo($payment->paymentable);
            return;
        }

        if ($payment->paymentable_id === $subscription->plan_id) {
            $subscription->renew();
            return;
        }

        $user->addBalance($payment->price_with_vat);

        $priceDetails = applyDiscountAndCalculatePriceDetails($payment->paid_amount, 0);

        $payment->update([
            'paymentable_id' => null,
            'paymentable_type' => null,
            'discount' => 0,
            'paid_amount_from_balance' => 0,
            'price' => $priceDetails->price,
            'vat' => $priceDetails->vat,
            'price_with_vat' => $priceDetails->price_with_vat,
        ]);
    }

    private function handleReservationGroup(Payment $payment): void
    {
        $payment->paymentable->reservations()->update([
            'is_paid' => true,
            'paid_at' => $payment->paid_at,
            'payment_source' => 'make_commerce'
        ]);

        CheckRefundSlots::dispatch($payment->paymentable_id);
    }

    private function handleDefault(Payment $payment): void
    {
        $payment->user->addBalance($payment->paid_amount);
    }
}
