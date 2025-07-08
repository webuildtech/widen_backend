<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Intervals\IntervalData;
use App\Data\Admin\Intervals\IntervalListData;
use App\Data\Admin\Intervals\IntervalSelectOptionData;
use App\Data\Admin\Intervals\IntervalStoreData;
use App\Data\Admin\Intervals\IntervalUpdateData;
use App\Http\Controllers\Controller;
use App\Models\Interval;
use App\Models\IntervalPrice;
use App\Services\IntervalService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IntervalController extends Controller
{
    public function __construct(
        protected IntervalService $intervalService
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
                AllowedFilter::scope('date_from_between'),
                AllowedFilter::scope('date_to_between'),
                AllowedFilter::scope('updated_at_between'),
                AllowedFilter::scope('global'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return IntervalListData::collect($companies);
    }

    public function store(IntervalStoreData $data): IntervalData
    {
        $interval = $this->intervalService->create($data);

        return IntervalData::from($interval);
    }

    public function show(Interval $interval): IntervalData
    {
        return IntervalData::from($interval);
    }

    public function update(IntervalUpdateData $data, Interval $interval): IntervalData
    {
        $interval = $this->intervalService->update($interval, $data);

        return IntervalData::from($interval);
    }

    public function destroy(Interval $interval): array
    {
        $interval->courts()->detach();

        $interval->prices->each(fn (IntervalPrice $intervalPrice) => $intervalPrice->groups()->detach());

        $interval->prices()->delete();

        $interval->delete();

        return [];
    }

    public function all()
    {
        $intervals = Interval::where('date_to', '>=', now())->get();

        return IntervalSelectOptionData::collect($intervals);
    }
}
