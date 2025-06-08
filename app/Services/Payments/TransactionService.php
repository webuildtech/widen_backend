<?php

namespace App\Services\Payments;

use App\Models\ReservationGroup;
use App\Models\User;
use App\Models\Guest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionService
{
    public function __construct(
        protected PaymentService $paymentService,
        protected MakeCommerceService $makeCommerceService,
        protected Request $request
    ) {}

    /**
     * Apdoroja rezervacijų grupės apmokėjimą
     */
    public function processReservationGroupPayment(ReservationGroup $group, User|Guest $owner): JsonResponse
    {
        $payment = $this->paymentService->createFromReservationGroup($group, $owner);

        if ($payment->paid_amount > 0) {
            $url = $this->makeCommerceService->createTransaction(
                $payment,
                $payment->owner->email,
                $this->request->ip()
            );

            return response()->json(['url' => $url], 201);
        }

        $this->paymentService->approve($payment);

        return response()->json(['balance' => $owner->balance ?? 0]);
    }
}
