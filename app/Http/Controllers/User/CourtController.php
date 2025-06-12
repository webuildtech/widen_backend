<?php

namespace App\Http\Controllers\User;

use App\Data\User\Courts\CourtFilterData;
use App\Data\User\Courts\CourtListData;
use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Services\PlanCourtTypeRuleService;
use App\Services\Slots\CourtSlotService;

class CourtController extends Controller
{
    public function __construct(
        protected CourtSlotService         $courtSlotService,
        protected PlanCourtTypeRuleService $planCourtTypeRuleService
    )
    {
    }

    public function index(CourtFilterData $data)
    {
        $user = auth()->guard('user')->user();

        $permissions = $this->planCourtTypeRuleService->getAllowedCourtTypesByDate($user, $data->date);

        $courts = Court::whereActive(true)
            ->with('courtType')
            ->whereHas('intervals')
            ->get()
            ->map(function (Court $court) use ($user, $permissions, $data) {
                $court->slots = $permissions[$court->court_type_id]
                    ? $this->courtSlotService->generateFreeSlots($court, $data->date, $user)
                    : collect();

                return $court;
            });

        return CourtListData::collect($courts);
    }
}
