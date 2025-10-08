<?php

namespace App\Http\Controllers\User;

use App\Data\User\Plans\PlanData;
use App\Data\User\Subscriptions\SubscribeData;
use App\Data\User\Subscriptions\SubscriptionData;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\DiscountCodeService;
use App\Services\Payments\MakeCommerceService;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class SubscriptionController extends Controller
{
    public function __construct(
        protected MakeCommerceService $makeCommerceService,
        protected PaymentService      $paymentService,
        protected DiscountCodeService $discountCodeService,
    )
    {
    }

    public function current(): SubscriptionData|JsonResponse
    {
        $user = auth()->user();

        $subscription = $user->subscription;

        if ($subscription) {
            return SubscriptionData::from([
                'plan_id' => $subscription->plan_id,
                'started_at' => $subscription->started_at,
                'expired_at' => $subscription->expired_at,
                'cancelled_at' => $subscription->cancelled_at,
            ]);
        }

        return response()->json([], 404);
    }

    public function subscribe(SubscribeData $data, Plan $plan): JsonResponse
    {
        if ($plan->is_default) {
            return response()->json(['error' => 'Šio plano prenumeruoti negalima.'], 405);
        }

        $user = auth()->user();

        $discountCode = null;

        if (is_string($data->discount_code)) {
            $result = $this->discountCodeService->validateCode($data->discount_code);

            if (!$result['valid']) {
                return response()->json(['message' => $result['message']], 406);
            }

            $discountCode = $result['discountCode'];
        }

        $subscription = $user->subscription;

        $payment = $this->paymentService->createFromPlan(
            $plan,
            $user,
            $subscription && $subscription->plan_id === $plan->id,
            $discountCode
        );

        if ($payment->paid_amount > 0) {
            try {
                $url = $this->makeCommerceService->createTransaction($payment, request()->ip());

                return response()->json(['url' => $url], 201);
            } catch (RuntimeException $e) {
                $this->paymentService->cancel($payment->refresh(), PaymentStatus::CANCELLED);

                return response()->json([
                    'message' => 'Atsiprašome, šiuo metu negalime susisiekti su mokėjimo paslaugų teikėju. Prašome pabandyti vėliau.'
                ], 500);
            }
        }

        $payment = $this->paymentService->approve($payment);

        return response()->json(['balance' => $payment->owner->balance]);
    }
}
