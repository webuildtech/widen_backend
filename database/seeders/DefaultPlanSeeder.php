<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Services\PlanService;

class DefaultPlanSeeder extends BasicSeeder
{
    public function __construct(
        protected PlanService $planService,
    )
    {
        parent::__construct();
    }

    public function run(): void
    {
        if ($this->isNotSeeded()) {
            $plan = Plan::create([
                'name' => 'Neregistruotas vartotojas / Vartotojas be plano',
                'type' => 'Default',
                'is_default' => true
            ]);

            $this->planService->initializeDefaults($plan);

            $this->saveSeed();
        }
    }
}
