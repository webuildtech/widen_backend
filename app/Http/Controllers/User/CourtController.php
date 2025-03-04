<?php

namespace App\Http\Controllers\User;

use App\Data\User\Courts\CourtData;
use App\Data\User\Courts\CourtSlotData;
use App\Data\User\Courts\CourtTimesData;
use App\Data\User\Courts\ListCourtData;
use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Services\CourtSlotService;
use Illuminate\Http\JsonResponse;

class CourtController extends Controller
{
    public function __construct(
      protected CourtSlotService $courtSlotService,
    ) {
    }

    public function index()
    {
        $courts = Court::whereActive(true)
            ->whereHas('intervals')
            ->get()
            ->map(function (Court $court) {
               $court->fast_slots = $this->courtSlotService->getBestSlots($court, now());

               return $court;
            });

        return ListCourtData::collect($courts);
    }

    public function show(Court $court): CourtData|JsonResponse
    {
        if (!$court->is_available) {
            return response()->json([], 403);
        }

        return CourtData::from($court);
    }

    public function times(Court $court, CourtTimesData $data): JsonResponse|array
    {
        if (!$court->is_available) {
            return response()->json([], 403);
        }

        return CourtSlotData::collect($this->courtSlotService->generateFreeSlots($court, $data->date));
    }
}
