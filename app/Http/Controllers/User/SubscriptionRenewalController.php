<?php

namespace App\Http\Controllers\User;

use App\Data\User\Subscriptions\SubscriptionRenewalData;
use App\Data\User\Subscriptions\SubscriptionRenewalLinkData;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Services\Payments\MakeCommerceService;
use App\Services\Payments\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class SubscriptionRenewalController extends Controller
{
    public function __construct(
        protected MakeCommerceService $makeCommerceService,
        protected PaymentService      $paymentService,
        protected SubscriptionService $subscriptionService,
    )
    {
    }

    public function show(SubscriptionRenewalLinkData $data, int $subscription): SubscriptionRenewalData|JsonResponse
    {
        $subscription = $this->subscriptionService->findRenewable($subscription, $data->until);

        if (!$subscription) {
            return $this->outdated();
        }

        $user = $subscription->subscriber;

        $price = $this->paymentService->planPriceForUser($subscription->plan, $user);
        $paidFromBalance = $user->getDeductedAmount($price);

        return new SubscriptionRenewalData(
            $subscription->plan->plan->name,
            $subscription->plan->periodicity_type,
            $subscription->expired_at,
            $price,
            $paidFromBalance,
            round($price - $paidFromBalance, 2),
        );
    }

    public function store(SubscriptionRenewalLinkData $data, int $subscription): JsonResponse
    {
        $subscription = $this->subscriptionService->findRenewable($subscription, $data->until);

        if (!$subscription) {
            return $this->outdated();
        }

        $payment = $this->paymentService->createFromPlanPrice($subscription->plan, $subscription->subscriber, true);

        if ($payment->paid_amount > 0) {
            try {
                $url = $this->makeCommerceService->createTransaction($payment, request()->ip());

                return response()->json(['url' => $url], 201);
            } catch (RuntimeException $e) {
                $this->paymentService->cancel($payment->refresh(), PaymentStatus::CANCELLED);

                return response()->json([
                    'message' => __('payments.provider_unavailable')
                ], 500);
            }
        }

        $this->paymentService->approve($payment);

        return response()->json();
    }

    private function outdated(): JsonResponse
    {
        return response()->json(['message' => __('subscriptions.renewal_link_outdated')], 410);
    }
}
