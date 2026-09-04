<?php

namespace App\Services\Payments;

use App\Enums\GameParticipantStatus;
use App\Jobs\CheckRefundSlots;
use App\Mail\BalanceTopUpMail;
use App\Mail\GameJoinedMail;
use App\Models\GameParticipant;
use App\Mail\PlanSubscribeAdminMail;
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
            'game' => $this->handleGame($payment),
            default => $this->handleDefault($payment)
        };
    }

    private function handleGame(Payment $payment): void
    {
        GameParticipant::wherePaymentId($payment->id)->update([
            'status' => GameParticipantStatus::CONFIRMED,
            'joined_at' => $payment->paid_at,
        ]);

        GameParticipant::wherePaymentId($payment->id)
            ->with(['game.courtType', 'game.court', 'user', 'addedBy'])
            ->get()
            ->each(fn(GameParticipant $participant) => Mail::queue(new GameJoinedMail($participant)));
    }

    private function handlePlanPrice(Payment $payment): void
    {
        $user = $payment->owner;

        $subscription = $user->subscription;
        $previousPlanName = $subscription?->plan?->plan?->name;

        if (!$subscription) {
            $action = PlanSubscribeAdminMail::ACTION_NEW;
            $user->subscribeTo($payment->paymentable);
        } elseif ($payment->paymentable_id === $subscription->plan_id) {
            $action = PlanSubscribeAdminMail::ACTION_RENEW;
            $subscription->renew();
        } else {
            $action = PlanSubscribeAdminMail::ACTION_SWITCH;
            $user->switchTo($payment->paymentable);
        }

        Mail::queue(new PlanSubscribeMail($payment, $payment->renew));

        // The free plan needs no staff attention, so only paid plans are announced.
        if ($payment->paymentable->price > 0 && ($adminEmail = config('mail.to_address'))) {
            Mail::to($adminEmail)->queue(new PlanSubscribeAdminMail($payment, $action, $previousPlanName));
        }
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
