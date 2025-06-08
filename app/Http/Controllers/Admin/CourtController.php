<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Courts\CourtData;
use App\Data\Admin\Courts\ListCourtData;
use App\Data\Admin\Courts\SelectCourtData;
use App\Data\Admin\Courts\StoreCourtData;
use App\Data\Admin\Courts\UpdateCourtData;
use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Services\CourtService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CourtController extends Controller
{
    public function __construct(
        protected CourtService $courtService
    )
    {
    }

    public function index()
    {
        $companies = QueryBuilder::for(Court::class)
            ->allowedSorts([
                'name',
                'inside_name',
                'active',
                'type',
                'updated_at'
            ])
            ->allowedFilters([
                'name',
                'inside_name',
                AllowedFilter::exact('type'),
                AllowedFilter::scope('updated_at_between'),
                AllowedFilter::scope('global'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return ListCourtData::collect($companies);
    }

    public function store(StoreCourtData $data): CourtData
    {
        $court = $this->courtService->create($data);

        return CourtData::from($court);
    }

    public function show(Court $court): CourtData
    {
        return CourtData::from($court);
    }

    public function update(UpdateCourtData $data, Court $court): CourtData
    {
        $court = $this->courtService->update($court, $data);

        return CourtData::from($court);
    }

    public function destroy(Court $court): array
    {
        $court->delete();

        return [];
    }

    public function all()
    {
        $courts = Court::all();

        return SelectCourtData::collect($courts);
    }
}
