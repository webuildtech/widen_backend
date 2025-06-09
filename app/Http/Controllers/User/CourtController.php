<?php

namespace App\Http\Controllers\User;

use App\Data\User\Courts\CourtFilterData;
use App\Data\User\Courts\CourtListData;
use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Services\Slots\CourtSlotService;

class CourtController extends Controller
{
    public function __construct(
      protected CourtSlotService $courtSlotService,
    ) {
    }

    public function index(CourtFilterData $data)
    {
        $courts = Court::whereActive(true)
            ->whereHas('intervals')
            ->get()
            ->map(function (Court $court) use ($data) {
               $court->slots = $this->courtSlotService->generateFreeSlots($court, $data->date, auth()->guard('user')->user());

               return $court;
            });

        return CourtListData::collect($courts);
    }
}
