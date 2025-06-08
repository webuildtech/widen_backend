<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class PlanService
{
    public function create(array $attributes): Model
    {
        $plan = Plan::create([
            ...$attributes,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Month,
            'active' => $attributes['active'] ?? 0,
        ]);

        return $plan->refresh();
    }
}
