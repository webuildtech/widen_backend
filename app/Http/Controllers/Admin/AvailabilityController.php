<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Availability\AvailabilityMainStatsData;
use App\Data\Admin\Availability\AvailabilityStatsWithCourtTypesData;
use App\Data\Admin\Availability\StatsFilterData;
use App\Services\Availability\AvailabilityStatsService;
use Carbon\CarbonImmutable;
use Illuminate\Routing\Controller;
use Spatie\LaravelData\Optional;

class AvailabilityController extends Controller
{
    public function __construct(
        protected AvailabilityStatsService $availabilityStatsService,
    )
    {
    }

    public function stats(): AvailabilityMainStatsData
    {
        $now = CarbonImmutable::now();
        $stats = $this->availabilityStatsService;

        $months = ['labels' => [], 'data' => []];

        foreach (range(-4, 4) as $i) {
            $cur = $now->addMonthsNoOverflow($i);
            $from = $cur->startOfMonth();
            $to = $cur->endOfMonth();

            $months['labels'][] = $from->isoFormat('YYYY [m.] MMM');
            $months['data'][] = $stats->getStatsByIntervalCourtType($from, $to);
        }

        $data = [
            'yesterday' => $stats->getStatsByIntervalCourtType($now->subDay()),
            'today' => $stats->getStatsByIntervalCourtType($now),
            'tomorrow' => $stats->getStatsByIntervalCourtType($now->addDay()),
            'months' => $months,
        ];

        return AvailabilityMainStatsData::from($data);
    }

    public function statsByInterval(StatsFilterData $data): AvailabilityStatsWithCourtTypesData
    {
        $stats = $this->availabilityStatsService->getStatsByIntervalCourtType(
            $data->date_from,
            $data->date_to instanceof Optional ? null : $data->date_to,
            $data->court_type_id instanceof Optional ? null : $data->court_type_id,
            $data->time_from instanceof Optional ? null : $data->time_from,
            $data->time_to instanceof Optional ? null : $data->time_to
        );

        return AvailabilityStatsWithCourtTypesData::from($stats);
    }
}
