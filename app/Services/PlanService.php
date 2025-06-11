<?php

namespace App\Services;

use App\Enums\Day;
use App\Models\CourtType;
use App\Models\Plan;
use App\Services\Slots\PlanCourtTypeSlotService;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class PlanService
{
    public function create(array $attributes): Plan
    {
        $plan = Plan::create([
            ...$attributes,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Year,
            'is_active' => $attributes['is_active'] ?? 0,
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
                foreach (PlanCourtTypeSlotService::DEFAULT_SLOTS as $startTime => $endTime) {
                    $planCourtTypeRule->slots()->create([
                        'day' => $day->value,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ]);
                }
            }
        });
    }
}
