<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\CourtSlot\CourtFilterData;
use App\Data\Admin\CourtSlot\CourtListData;
use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Services\PlanCourtTypeRuleService;
use App\Services\Slots\CourtSlotService;

class CourtSlotController extends Controller
{
    public function __construct(
        protected CourtSlotService         $courtSlotService,
        protected PlanCourtTypeRuleService $planCourtTypeRuleService
    )
    {
    }

    public function __invoke(CourtFilterData $data)
    {
        $courts = Court::query()
            ->with('courtType')
            ->whereHas('intervals');

        if ($data->courtTypeId) {
            $courts->where('court_type_id', $data->courtTypeId);
        }

        $courts = $courts->get()
            ->each(fn(Court $court) => $court->slots = $this->courtSlotService->generateFreeSlots($court, $data->date, null, false));

        return CourtListData::collect($courts);
    }
}
