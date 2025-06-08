<?php

namespace App\Responders\Reservations;

use App\Models\Guest;
use App\Models\ReservationGroup;
use App\Models\User;
use App\Services\Payments\MakeCommerceService;
use App\Services\Payments\PaymentService;
use App\Support\ServiceResponse;
use Illuminate\Http\Request;

class ReservationPaymentResponder
{
    public function __construct(
        protected PaymentService $paymentService,
        protected MakeCommerceService $makeCommerceService,
        protected Request $request
    ) {}

    public function handle(ReservationGroup $group, User|Guest $owner): ServiceResponse
    {
        $payment = $this->paymentService->createFromReservationGroup($group, $owner);

        if ($payment->paid_amount > 0) {
            $url = $this->makeCommerceService->createTransaction($payment, $payment->owner->email, $this->request->ip());

            return ServiceResponse::success(['url' => $url], 201);
        }

        $this->paymentService->approve($payment);

        return ServiceResponse::success(['balance' => $owner->balance ?? 0]);
    }
}
