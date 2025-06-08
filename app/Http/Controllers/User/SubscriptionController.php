<?php

namespace App\Http\Controllers\User;

use App\Data\User\Plans\PlanData;
use App\Data\User\Subscriptions\SubscriptionData;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Payments\MakeCommerceService;
use App\Services\Payments\PaymentService;
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
            ]);
        }

        return response()->json([], 404);
    }

    public function subscribe(Plan $plan): JsonResponse
    {
        $user = auth()->user();

        $subscription = $user->subscription ?? $user->lastSubscription();

        if ($subscription && $subscription->plan_id !== $plan->id) {
            if (!$subscription->is_overdue || ($subscription->is_overdue && !$subscription->canceled_at)) {
                return response()->json(['error' => 'Jau turite kita aktyvią prenumeratą!'], 405);
            }
        }

        $payment = $this->paymentService->createFromPlan($plan, $user, $subscription && !$subscription->canceled_at);

        if ($payment->paid_amount > 0) {
            $url = $this->makeCommerceService->createTransaction($payment, $payment->user->email, request()->ip());

            return response()->json(['url' => $url], 201);
        }

        $payment = $this->paymentService->approve($payment);

        return response()->json(['balance' => $payment->user->balance]);
    }
}
