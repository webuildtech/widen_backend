<?php

namespace App\Http\Controllers\User;

use App\Data\User\Courts\CourtData;
use App\Data\User\Courts\ListCourtData;
use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Services\IntervalTimesService;
use Carbon\Carbon;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::whereActive(true)
            ->whereHas('intervals')
            ->get();

        return ListCourtData::collect($courts);
    }

    public function show(Court $court): CourtData
    {
        if (!$court->active || !$court->intervals()->exists()) {
            abort(403);
        }

        return CourtData::from($court);
    }

    public function times()
    {
        $date = request()->get('date');

        $date1 = Carbon::parse($date); // Example date

        $court = Court::first();
        $intervalTimesService = new IntervalTimesService();

        return $intervalTimesService->getIntervalTimes($court, $date1);
    }
}
