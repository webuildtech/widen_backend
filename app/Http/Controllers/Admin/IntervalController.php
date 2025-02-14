<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Intervals\IntervalData;
use App\Data\Admin\Intervals\ListIntervalData;
use App\Data\Admin\Intervals\StoreIntervalData;
use App\Data\Admin\Intervals\UpdateIntervalData;
use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\Models\IntervalRepositoryInterface;
use App\Models\Interval;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IntervalController extends Controller
{
    public function __construct(
        protected IntervalRepositoryInterface $intervalRepository
    )
    {
    }

    public function index()
    {
        $companies = QueryBuilder::for(Interval::class)
            ->allowedSorts([
                'name',
                'inside_name',
                'date_from',
                'date_to',
                'updated_at'
            ])
            ->allowedFilters([
                'name',
                'inside_name',
                AllowedFilter::scope('date_between'),
                AllowedFilter::scope('updated_at_between'),
                AllowedFilter::scope('global'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return ListIntervalData::collect($companies);
    }

    public function store(StoreIntervalData $data): IntervalData
    {
        $interval = $this->intervalRepository->create($data);

        return IntervalData::from($interval);
    }

    public function show(Interval $interval): IntervalData
    {
        return IntervalData::from($interval);
    }

    public function update(UpdateIntervalData $data, Interval $interval): IntervalData
    {
        $interval = $this->intervalRepository->update($interval, $data);

        return IntervalData::from($interval);
    }

    public function destroy(Interval $interval): array
    {
        $this->intervalRepository->delete($interval);

        return [];
    }
}
