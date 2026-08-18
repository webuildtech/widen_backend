<?php

namespace App\Responders\Games;

use App\Enums\PaymentStatus;
use App\Models\DiscountCode;
use App\Models\Game;
use App\Models\User;
use App\Services\Payments\MakeCommerceService;
use App\Services\Payments\PaymentService;
use App\Support\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use RuntimeException;

class GameJoinPaymentResponder
{
    public function __construct(
        protected PaymentService      $paymentService,
        protected MakeCommerceService $makeCommerceService,
        protected Request             $request,
    )
    {
    }

    public function handle(Game $game, User $user, Collection $participants, ?DiscountCode $discountCode = null): ServiceResponse
    {
        $payment = $this->paymentService->createFromGameParticipants($game, $user, $participants, $discountCode);

        if ($payment->paid_amount > 0) {
            try {
                $url = $this->makeCommerceService->createTransaction($payment, $this->request->ip());

                return ServiceResponse::success(['url' => $url], 201);
            } catch (RuntimeException) {
                $this->paymentService->cancel($payment->refresh(), PaymentStatus::CANCELLED);

                return ServiceResponse::error(__('payments.provider_unavailable'), 500);
            }
        }

        $this->paymentService->approve($payment);

        return ServiceResponse::success(['balance' => $user->balance]);
    }
}
