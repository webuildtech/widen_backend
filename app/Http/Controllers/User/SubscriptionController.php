<?php

namespace App\Http\Controllers\User;

use App\Data\User\Plans\PlanData;
use App\Data\User\Subscriptions\SubscriptionData;
use App\Enums\FeatureType;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\MakeCommerceService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        protected MakeCommerceService $makeCommerceService,
        protected PaymentService      $paymentService,
    )
    {
    }

    public function current(): SubscriptionData|JsonResponse
    {
        $user = auth()->user();

        $subscription = $user->lastSubscription();

        if ($subscription) {
            return SubscriptionData::from([
                'started_at' => $subscription->started_at,
                'expired_at' => $subscription->expired_at,
                'cancelled_at' => $subscription->cancelled_at,
                'plan' => PlanData::from($subscription->plan),
                FeatureType::RESERVATION_PER_WEEK->value => $user->getCurrentConsumption(FeatureType::RESERVATION_PER_WEEK->value)
            ]);
        }

        return response()->json([], 404);
    }

    public function subscribe(Plan $plan): JsonResponse
    {
        $user = auth()->user();

        if ($user->subscription) {
            return response()->json(['error' => 'Jau turite aktyvią prenumeratą!'], 405);
        }

        $payment = $this->paymentService->createFromPlan($plan, $user);

        if ($payment->paid_amount > 0) {
            $url = $this->makeCommerceService->createTransaction($payment, $payment->user->email, request()->ip());

            return response()->json(['url' => $url], 201);
        }

        $payment = $this->paymentService->approve($payment);

        return response()->json(['balance' => $payment->user->balance]);
    }
}
