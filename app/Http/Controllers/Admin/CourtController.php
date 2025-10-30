<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Courts\CourtData;
use App\Data\Admin\Courts\CourtListData;
use App\Data\Admin\Courts\CourtSelectOptionData;
use App\Data\Admin\Courts\CourtStoreData;
use App\Data\Admin\Courts\CourtUpdateData;
use App\Http\Controllers\Controller;
use App\Models\AvailabilitySlot;
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
                'court_type_id',
                'updated_at'
            ])
            ->allowedFilters([
                'name',
                'inside_name',
                AllowedFilter::exact('court_type_id'),
                AllowedFilter::scope('updated_at_between'),
                AllowedFilter::scope('global'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return CourtListData::collect($companies);
    }

    public function store(CourtStoreData $data): CourtData
    {
        $court = $this->courtService->create($data);

        return CourtData::from($court);
    }

    public function show(Court $court): CourtData
    {
        return CourtData::from($court);
    }

    public function update(CourtUpdateData $data, Court $court): CourtData
    {
        $court = $this->courtService->update($court, $data);

        return CourtData::from($court);
    }

    public function destroy(Court $court): array
    {
        $court->clearMediaCollection('logo');

        $court->intervals()->detach();

        $court->downtimes()->delete();

        AvailabilitySlot::query()
            ->where('court_id', $court->id)
            ->where('date', '>=', now()->toDateString())
            ->delete();

        $court->delete();

        return [];
    }

    public function all()
    {
        $courts = Court::all();

        return CourtSelectOptionData::collect($courts);
    }
}
