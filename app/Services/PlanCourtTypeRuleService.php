<?php

namespace App\Services;

use App\Models\PlanCourtTypeRule;
use App\Models\User;
use Carbon\Carbon;

class PlanCourtTypeRuleService
{
    public function __construct(
        protected PlanService $planService
    )
    {
    }

    public function update(PlanCourtTypeRule $planCourtTypeRule, array $attributes): PlanCourtTypeRule
    {
        if (isset($attributes['slots'])) {
            $planCourtTypeRule->slots()->forceDelete();
            $planCourtTypeRule->slots()->createMany($attributes['slots']);
            unset($attributes['slots']);
        }

        $planCourtTypeRule->update($attributes);

        return $planCourtTypeRule->refresh();
    }

    public function getMaxDaysInAdvance(User $user = null, int $courtTypeId = null): int
    {
        $plan = $this->planService->getByUser($user);

        $courtTypeRules = $plan->courtTypeRules();

        if($courtTypeId) {
            $courtTypeRules->where('court_type_id', $courtTypeId);
        }

        return (int) $courtTypeRules->max('max_days_in_advance');
    }

    public function getCancelHoursBefore(User $user = null, int $courtTypeId = null): int
    {
        $plan = $this->planService->getByUser($user);

        $courtTypeRules = $plan->courtTypeRules();

        if($courtTypeId) {
            $courtTypeRules->where('court_type_id', $courtTypeId);
        }

        return (int) $courtTypeRules->max('cancel_hours_before');
    }

    public function getAllowedCourtTypesByDate(User $user = null, Carbon $date)
    {
        $plan = $this->planService->getByUser($user);

        $diffInDays = now()->diffInDays($date);

        return $plan->courtTypeRules->mapWithKeys(fn (PlanCourtTypeRule $courtTypeRule) => [
            $courtTypeRule->court_type_id => $courtTypeRule->max_days_in_advance >= $diffInDays
        ]);
    }
}
