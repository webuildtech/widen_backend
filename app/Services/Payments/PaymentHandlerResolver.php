<?php

namespace App\Services\Payments;

use App\Jobs\CheckRefundSlots;
use App\Mail\BalanceTopUpMail;
use App\Mail\PlanSubscribeMail;
use App\Mail\ReservationPaidMail;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;

class PaymentHandlerResolver
{
    public function handle(Payment $payment): void
    {
        match ($payment->paymentable_type) {
            'planPrice' => $this->handlePlanPrice($payment),
            'reservationGroup' => $this->handleReservationGroup($payment),
            default => $this->handleDefault($payment)
        };
    }

    private function handlePlanPrice(Payment $payment): void
    {
        $user = $payment->owner;

        $subscription = $user->subscription;

        if (!$subscription) {
            $user->subscribeTo($payment->paymentable);
        } elseif ($payment->paymentable_id === $subscription->plan_id) {
            $subscription->renew();
        } else {
            $user->switchTo($payment->paymentable);
        }

        Mail::queue(new PlanSubscribeMail($payment, $payment->renew));
    }

    private function handleReservationGroup(Payment $payment): void
    {
        $payment->paymentable->reservations()->update([
            'is_paid' => true,
            'paid_at' => $payment->paid_at,
            'payment_source' => 'make_commerce'
        ]);

        CheckRefundSlots::dispatch($payment->paymentable_id);

        Mail::queue(new ReservationPaidMail($payment));
    }

    private function handleDefault(Payment $payment): void
    {
        $payment->owner->addBalance($payment->paid_amount);

        Mail::queue(new BalanceTopUpMail($payment));
    }
}
