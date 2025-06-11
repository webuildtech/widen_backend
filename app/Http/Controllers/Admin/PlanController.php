<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Plans\PlanListData;
use App\Data\Admin\Plans\PlanData;
use App\Data\Admin\Plans\PlanSelectOptionData;
use App\Data\Admin\Plans\PlanStoreData;
use App\Data\Admin\Plans\PlanUpdateData;
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

        return PlanListData::collect($plans);
    }

    public function store(PlanStoreData $data): PlanData
    {
        $plan = $this->planService->create($data->toArray());

        return PlanData::from($plan);
    }

    public function show(Plan $plan): PlanData
    {
        return PlanData::from($plan);
    }

    public function update(PlanUpdateData $data, Plan $plan): PlanData
    {
        $plan->update($data->all());

        return PlanData::from($plan);
    }

    public function destroy(Plan $plan)
    {
        if ($plan->is_default) {
            return response()->json(['message' => 'Negalite ištrinti default plano!'], 406);
        }

        $plan->delete();

        return [];
    }

    public function all()
    {
        return PlanSelectOptionData::collect(Plan::all());
    }
}
