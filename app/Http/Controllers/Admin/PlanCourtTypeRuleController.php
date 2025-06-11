<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\PlanCourtTypeRules\PlanCourtTypeRuleData;
use App\Data\Admin\PlanCourtTypeRules\PlanCourtTypeRuleListData;
use App\Data\Admin\PlanCourtTypeRules\PlanCourtTypeRuleUpdateData;
use App\Http\Controllers\Controller;
use App\Models\PlanCourtTypeRule;
use App\Services\PlanCourtTypeRuleService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PlanCourtTypeRuleController extends Controller
{
    public function __construct(
        protected PlanCourtTypeRuleService $planCourtTypeRuleService,
    )
    {
    }

    public function index()
    {
        $plans = QueryBuilder::for(PlanCourtTypeRule::class)
            ->with('courtType')
            ->allowedSorts([
                'court_type_id',
                'max_days_in_advance',
                'cancel_hours_before',
                'updated_at'
            ])
            ->allowedFilters([
                AllowedFilter::exact('plan_id'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return PlanCourtTypeRuleListData::collect($plans);
    }

    public function show(PlanCourtTypeRule $planCourtTypeRule): PlanCourtTypeRuleData
    {
        return PlanCourtTypeRuleData::from($planCourtTypeRule);
    }

    public function update(PlanCourtTypeRuleUpdateData $data, PlanCourtTypeRule $planCourtTypeRule): PlanCourtTypeRuleData
    {
        $planCourtTypeRule = $this->planCourtTypeRuleService->update($planCourtTypeRule, $data->toArray());

        return PlanCourtTypeRuleData::from($planCourtTypeRule);
    }
}
