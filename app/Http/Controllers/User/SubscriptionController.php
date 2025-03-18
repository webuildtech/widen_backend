<?php

namespace App\Http\Controllers\User;

use App\Data\User\Plans\PlanData;
use App\Data\User\Subscriptions\SubscriptionData;
use App\Enums\FeatureType;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
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

    public function subscribe(Plan $plan)
    {
        $user = auth()->user();

        if ($user->subscription) {
            return response()->json(['error' => 'Jau turite aktyvią prenumeratą!'], 405);
        }

        $user->subscribeTo($plan);

        return response()->json();

    }
}
