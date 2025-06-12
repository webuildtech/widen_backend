<?php

namespace App\Services\Slots;

use App\Models\CourtType;
use App\Models\Plan;
use App\Models\User;
use App\Services\PlanService;
use Carbon\Carbon;

class PlanCourtTypeRuleSlotService
{
    public function __construct(
        protected PlanService $planService
    )
    {
    }

    const DEFAULT_SLOTS = [
        '09:00' => '09:30',
        '09:30' => '10:00',
        '10:00' => '10:30',
        '10:30' => '11:00',
        '11:00' => '11:30',
        '11:30' => '12:00',
        '12:00' => '12:30',
        '12:30' => '13:00',
        '13:00' => '13:30',
        '13:30' => '14:00',
        '14:00' => '14:30',
        '14:30' => '15:00',
        '15:00' => '15:30',
        '15:30' => '16:00',
        '16:00' => '16:30',
        '16:30' => '17:00',
    ];

    public function getForDateUserAndCourtType(CourtType $courtType, Carbon $date, User $user = null): array
    {
        $plan = $this->planService->getByUser($user);

        $planCourtTypeRule = $plan->courtTypeRules()->where('court_type_id', $courtType->id)->first();

        return $planCourtTypeRule->slots()
            ->where('day', strtolower($date->format('D')))
            ->pluck('end_time', 'start_time')
            ->toArray();
    }
}
