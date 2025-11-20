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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use LucasDotVin\Soulbscription\Models\Subscription;
use Spatie\LaravelData\Optional;
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
            ->with('prices')
            ->allowedSorts([
                'name',
                'type',
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
        $plan = $this->planService->create($data->except('features', 'prices')->toArray());

        $this->planService->syncFeatures($plan, $data->features);
        $this->planService->syncPrices($plan, $data->prices);

        return PlanData::from($plan->load(['prices', 'features', 'features.subFeatures']));
    }

    public function show(Plan $plan): PlanData
    {
        return PlanData::from($plan->load(['prices', 'features', 'features.subFeatures']));
    }

    public function update(PlanUpdateData $data, Plan $plan): JsonResponse|PlanData
    {
        $ids = $plan->prices()->whereNotIn('id', Arr::pluck($data->prices, 'id'))->pluck('id');

        if (Subscription::whereIn('plan_id', $ids)->exists()) {
            return response()->json(['message' => 'Negalima ištrinti plano kainos, nes yra aktyvių prenumeratų!'], 406);
        }

        $plan->update($data->except('features', 'prices')->all());

        $this->planService->syncFeatures($plan, $data->features);
        $this->planService->syncPrices($plan, $data->prices);

        return PlanData::from($plan->load(['prices', 'features', 'features.subFeatures']));
    }

    public function destroy(Plan $plan)
    {
        if ($plan->is_default) {
            return response()->json(['message' => 'Negalite ištrinti default plano!'], 406);
        }

        if (Subscription::whereIn('plan_id', $plan->prices()->pluck('id'))->exists()) {
            return response()->json(['message' => 'Negalima ištrinti plano, nes yra aktyvių prenumeratų!'], 406);
        }

        $plan->delete();

        return [];
    }

    public function all()
    {
        return PlanSelectOptionData::collect(Plan::whereIsDefault(false)->get());
    }
}
