<?php

namespace App\Http\Controllers\User;

use App\Data\User\Courts\CourtData;
use App\Data\User\Courts\CourtSlotData;
use App\Data\User\Courts\CourtFilterData;
use App\Data\User\Courts\CourtListData;
use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Services\Slots\CourtSlotService;
use Illuminate\Http\JsonResponse;

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

    public function show(Court $court): CourtData|JsonResponse
    {
        if (!$court->is_available) {
            return response()->json([], 403);
        }

        return CourtData::from($court);
    }

    public function times(Court $court, CourtFilterData $data): JsonResponse|array
    {
        if (!$court->is_available) {
            return response()->json([], 403);
        }

        return CourtSlotData::collect($this->courtSlotService->generateFreeSlots(
            $court,
            $data->date,
            auth()->guard('user')->user()
        ));
    }
}
