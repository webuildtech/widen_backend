<?php

namespace App\Services;

use App\Data\Admin\Plans\Features\PlanFeatureInsertData;
use App\Data\Admin\Plans\Prices\PlanPriceInsertData;
use App\Enums\Day;
use App\Models\CourtType;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\User;
use App\Services\Slots\PlanCourtTypeRuleSlotService;

class PlanService
{
    public function getByUser(User $user = null): Plan
    {
        if ($user && $user->subscription) {
            return $user->subscription->plan->plan;
        }

        return Plan::whereIsDefault(true)->first();
    }

    public function create(array $attributes): Plan
    {
        $plan = Plan::create([
            ...$attributes,
            'is_active' => $attributes['is_active'] ?? 0,
            'is_popular' => $attributes['is_popular'] ?? 0,
        ]);

        $this->initializeDefaults($plan);

        return $plan->refresh();
    }

    public function initializeDefaults(Plan $plan): void
    {
        CourtType::all()->each(function (CourtType $courtType) use ($plan) {
            $planCourtTypeRule = $plan->courtTypeRules()->create([
                'court_type_id' => $courtType->id,
                'max_days_in_advance' => 7,
                'cancel_hours_before' => 24
            ]);

            foreach (Day::cases() as $day) {
                foreach (PlanCourtTypeRuleSlotService::DEFAULT_SLOTS as $startTime => $endTime) {
                    $planCourtTypeRule->slots()->create([
                        'day' => $day->value,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ]);
                }
            }
        });
    }

    /**
     * @param Plan $plan
     * @var $features array<int, PlanFeatureInsertData>
     */
    public function syncFeatures(Plan $plan, array $features): void
    {
        $ids = [];

        foreach ($features as $featureData) {
            $feature = $plan->features()->updateOrCreate(
                ['id' => $featureData->id],
                ['label' => $featureData->label]
            );

            $ids[] = $feature->id;

            foreach ($featureData->subFeatures as $subFeatureData) {
                $subFeature = $feature->subFeatures()->updateOrCreate(
                    ['id' => $subFeatureData->id, 'plan_id' => $plan->id],
                    ['label' => $subFeatureData->label]
                );

                $ids[] = $subFeature->id;
            }
        }

        PlanFeature::wherePlanId($plan->id)->whereNotIn('id', $ids)->delete();
    }

    /**
     * @param Plan $plan
     * @var $prices array<int, PlanPriceInsertData>
     */
    public function syncPrices(Plan $plan, array $prices): void
    {
        $ids = [];

        foreach ($prices as $priceData) {
            $price = $plan->prices()->updateOrCreate(
                ['id' => $priceData->id],
                [
                    'price' => $priceData->price,
                    'previous_price' => $priceData->previous_price,
                    'periodicity' => 1,
                    'periodicity_type' => $priceData->periodicity_type
                ]
            );

            $ids[] = $price->id;
        }

        $plan->prices()->whereNotIn('id', $ids)->delete();
    }
}
