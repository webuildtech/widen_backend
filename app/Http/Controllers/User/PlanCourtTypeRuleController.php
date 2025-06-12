<?php

namespace App\Http\Controllers\User;

use App\Data\User\PlanCourtTypeRules\PlanCourtTypeRuleData;
use App\Http\Controllers\Controller;
use App\Services\PlanService;

class PlanCourtTypeRuleController extends Controller
{
    public function __construct(
        protected PlanService $planService,
    )
    {
    }

    public function index()
    {
        $plan = $this->planService->getByUser(auth()->guard('user')->user());

        return PlanCourtTypeRuleData::collect($plan->courtTypeRules);
    }
}
