<?php

namespace App\Services\Games;

use App\Enums\GameParticipantStatus;
use App\Enums\PaymentStatus;
use App\Mail\GameCanceledMail;
use App\Models\Game;
use App\Models\GameParticipant;
use App\Models\User;
use App\Services\Payments\PaymentService;
use Illuminate\Support\Facades\Mail;

class GameRefundService
{
    public function __construct(
        protected PaymentService $paymentService,
    )
    {
    }

    public function refundAll(Game $game, ?string $reason = null, bool $notify = true): void
    {
        $participants = $game->participants()
            ->holdingSpot()
            ->with(['payment.owner', 'user'])
            ->get();

        $participants
            ->where('status', GameParticipantStatus::PENDING)
            ->pluck('payment')
            ->filter()
            ->unique('id')
            ->each(fn($payment) => $this->paymentService->cancel($payment, PaymentStatus::CANCELLED));

        $participants
            ->where('status', GameParticipantStatus::CONFIRMED)
            ->each(function (GameParticipant $participant) use ($game, $reason, $notify) {
                $refunded = round((float)$participant->price_with_vat - (float)$participant->refunded_amount, 2);

                $this->refund($participant, $reason);

                if ($notify) {
                    Mail::queue(new GameCanceledMail($game, $participant, $refunded, $reason));
                }
            });
    }

    public function refund(GameParticipant $participant, ?string $reason = null): GameParticipant
    {
        if ($participant->status === GameParticipantStatus::CANCELED) {
            return $participant;
        }

        $amount = round((float)$participant->price_with_vat - (float)$participant->refunded_amount, 2);
        $payment = $participant->payment;
        $payer = $payment?->owner;

        if ($amount > 0 && $payment?->status === PaymentStatus::PAID && $payer instanceof User) {
            $payer->addBalance($amount);

            $payment->increment('refunded_amount', $amount);
            $payment->increment('refunded_amount_to_balance', $amount);

            $participant->refunded_amount = $participant->price_with_vat;
        }

        $participant->status = GameParticipantStatus::CANCELED;
        $participant->canceled_at = now();
        $participant->save();

        return $participant;
    }
}
