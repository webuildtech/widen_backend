<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Downtimes\DowntimeData;
use App\Data\Admin\Downtimes\DowntimeListData;
use App\Data\Admin\Downtimes\DowntimeInputData;
use App\Http\Controllers\Controller;
use App\Models\Downtime;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DowntimeController extends Controller
{
    public function index()
    {
        $downtimes = QueryBuilder::for(Downtime::class)
            ->allowedSorts([
                'court_id',
                'date_from',
                'date_to',
                'start_time',
                'end_time',
                'comment',
                'updated_at'
            ])
            ->allowedFilters([
                'comment',
                AllowedFilter::exact('court_id'),
                AllowedFilter::scope('date_from_between'),
                AllowedFilter::scope('date_to_between'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return DowntimeListData::collect($downtimes);
    }

    public function store(DowntimeInputData $data): DowntimeData
    {
        $downtime = Downtime::create($data->all());

        return DowntimeData::from($downtime);
    }

    public function show(Downtime $downtime): DowntimeData
    {
        return DowntimeData::from($downtime);
    }

    public function update(DowntimeInputData $data, Downtime $downtime): DowntimeData
    {
        $downtime->update($data->all());

        return DowntimeData::from($downtime);
    }

    public function destroy(Downtime $downtime): array
    {
        $downtime->delete();

        return [];
    }
}
