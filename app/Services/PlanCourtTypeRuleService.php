<?php

namespace App\Services;

use App\Models\PlanCourtTypeRule;

class PlanCourtTypeRuleService
{
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
}
