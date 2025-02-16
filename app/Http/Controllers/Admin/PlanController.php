<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Plans\ListPlanData;
use App\Data\Admin\Plans\PlanData;
use App\Data\Admin\Plans\SelectPlanData;
use App\Data\Admin\Plans\StorePlanData;
use App\Data\Admin\Plans\UpdatePlanData;
use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\Models\PlanRepositoryInterface;
use App\Models\Plan;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PlanController extends Controller
{
    public function __construct(
        protected PlanRepositoryInterface $planRepository
    )
    {
    }

    public function index()
    {
        $plans = QueryBuilder::for(Plan::class)
            ->allowedSorts([
                'name',
                'type',
                'reservations_per_week',
                'cancel_before',
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
        $plan = $this->planRepository->create($data);

        return PlanData::from($plan);
    }

    public function show(Plan $plan): PlanData
    {
        return PlanData::from($plan);
    }

    public function update(UpdatePlanData $data, Plan $plan): PlanData
    {
        $plan = $this->planRepository->update($plan, $data);

        return PlanData::from($plan);
    }

    public function destroy(Plan $plan): array
    {
        $this->planRepository->delete($plan);

        return [];
    }

    public function all()
    {
        return SelectPlanData::collect(Plan::all());
    }
}
