<?php

namespace App\Services;

use App\Data\Admin\Dashboard\PlanSubscriptionMetricData;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\Subscription;
use Illuminate\Support\Collection;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class SubscriptionService
{
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
