<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Downtimes\DowntimeData;
use App\Data\Admin\Downtimes\ListDowntimeData;
use App\Data\Admin\Downtimes\StoreUpdateDowntimeData;
use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\Models\DowntimeRepositoryInterface;
use App\Models\Downtime;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DowntimeController extends Controller
{
    public function __construct(
        protected DowntimeRepositoryInterface $downtimeRepository
    )
    {
    }

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
                AllowedFilter::scope('date_between'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return ListDowntimeData::collect($downtimes);
    }

    public function store(StoreUpdateDowntimeData $data): DowntimeData
    {
        $downtime = $this->downtimeRepository->create($data);

        return DowntimeData::from($downtime);
    }

    public function show(Downtime $downtime): DowntimeData
    {
        return DowntimeData::from($downtime);
    }

    public function update(StoreUpdateDowntimeData $data, Downtime $downtime): DowntimeData
    {
        $downtime = $this->downtimeRepository->update($downtime, $data);

        return DowntimeData::from($downtime);
    }

    public function destroy(Downtime $downtime): array
    {
        $this->downtimeRepository->delete($downtime);

        return [];
    }
}
