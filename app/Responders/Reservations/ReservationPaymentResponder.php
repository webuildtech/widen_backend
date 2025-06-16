<?php

namespace App\Responders\Reservations;

use App\Models\DiscountCode;
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

    public function handle(ReservationGroup $group, User|Guest $owner, DiscountCode $discountCode = null): ServiceResponse
    {
        $payment = $this->paymentService->createFromReservationGroup($group, $owner, false, $discountCode);

        if ($payment->paid_amount > 0) {
            $url = $this->makeCommerceService->createTransaction($payment, $this->request->ip());

            return ServiceResponse::success(['url' => $url], 201);
        }

        $this->paymentService->approve($payment);

        return ServiceResponse::success(['balance' => $owner->balance ?? 0]);
    }
}
