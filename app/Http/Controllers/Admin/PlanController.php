<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Plans\ListPlanData;
use App\Data\Admin\Plans\PlanData;
use App\Data\Admin\Plans\SelectPlanData;
use App\Data\Admin\Plans\StorePlanData;
use App\Data\Admin\Plans\UpdatePlanData;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\PlanService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PlanController extends Controller
{
    public function __construct(
        protected PlanService $planService
    )
    {
    }

    public function index()
    {
        $plans = QueryBuilder::for(Plan::class)
            ->allowedSorts([
                'name',
                'type',
                'price',
                'active',
                'updated_at'
            ])
            ->allowedFilters([
                'name',
                'type',
                AllowedFilter::scope('updated_at_between'),
                AllowedFilter::scope('global'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return ListPlanData::collect($plans);
    }

    public function store(StorePlanData $data): PlanData
    {
        $plan = $this->planService->create($data->all());

        return PlanData::from($plan);
    }

    public function show(Plan $plan): PlanData
    {
        return PlanData::from($plan);
    }

    public function update(UpdatePlanData $data, Plan $plan): PlanData
    {
        $plan->update($data->all());

        return PlanData::from($plan);
    }

    public function destroy(Plan $plan): array
    {
        $plan->delete();

        return [];
    }

    public function all()
    {
        return SelectPlanData::collect(Plan::all());
    }
}
