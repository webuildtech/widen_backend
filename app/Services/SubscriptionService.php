<?php

namespace App\Services;

use App\Data\Admin\Dashboard\PlanSubscriptionMetricData;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\Subscription;
use App\Support\FrontendUrl;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class SubscriptionService
{
    private const RENEWAL_LINK_VALID_DAYS_AFTER_EXPIRY = 30;

    public function renewalUrl(Subscription $subscription): string
    {
        $signedUrl = URL::temporarySignedRoute(
            'subscriptions.renewal',
            $subscription->expired_at->copy()->addDays(self::RENEWAL_LINK_VALID_DAYS_AFTER_EXPIRY),
            ['subscription' => $subscription->id, 'until' => $subscription->expired_at->timestamp],
            absolute: false,
        );

        return FrontendUrl::to("/plan-renewal/$subscription->id", $subscription->subscriber->locale)
            . '?' . parse_url($signedUrl, PHP_URL_QUERY);
    }

    public function findRenewable(int $id, int $until): ?Subscription
    {
        $subscription = Subscription::withExpired()->notCanceled()->with(['plan.plan', 'subscriber'])->find($id);

        if (!$subscription?->plan || !$subscription->subscriber || $subscription->expired_at->timestamp !== $until) {
            return null;
        }

        $current = $subscription->subscriber->subscription;

        return !$current || $current->is($subscription) ? $subscription : null;
    }

    public function getActiveMetricsByPlan(): array
    {
        // Subscriptions point at a plan price, so the counts are resolved per price and then folded into their plan.
        $countsByPrice = Subscription::query()
            ->selectRaw('plan_id, count(*) as aggregate')
            ->groupBy('plan_id')
            ->pluck('aggregate', 'plan_id');

        $prices = PlanPrice::withTrashed()
            ->whereIn('id', $countsByPrice->keys())
            ->get()
            ->groupBy('plan_id');

        return Plan::query()
            ->whereIsDefault(false)
            ->orderBy('name')
            ->get()
            ->map(fn(Plan $plan) => new PlanSubscriptionMetricData(
                $plan->id,
                $plan->name,
                $plan->type,
                $this->countPrices($prices->get($plan->id), $countsByPrice),
                $this->countPrices($prices->get($plan->id), $countsByPrice, PeriodicityType::Month),
                $this->countPrices($prices->get($plan->id), $countsByPrice, PeriodicityType::Year),
            ))
            ->all();
    }

    private function countPrices(?Collection $prices, Collection $countsByPrice, ?string $periodicityType = null): int
    {
        if (!$prices) {
            return 0;
        }

        return $prices
            ->when($periodicityType, fn(Collection $prices) => $prices->where('periodicity_type', $periodicityType))
            ->sum(fn(PlanPrice $price) => $countsByPrice->get($price->id, 0));
    }
}
