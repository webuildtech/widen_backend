<?php

namespace App\Data\User\Courts;

use App\Services\PlanCourtTypeRuleService;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\BeforeOrEqual;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtFilterData extends Data
{
    public function __construct(
        public Carbon $date,
    )
    {
    }

    public static function rules(ValidationContext $context, PlanCourtTypeRuleService $planCourtTypeRuleService): array
    {
        $user = auth()->guard('user')->user();

        return [
            'date' => [
                new Required(),
                new AfterOrEqual(Carbon::today()),
                new BeforeOrEqual(Carbon::today()->addDays($planCourtTypeRuleService->getMaxDaysInAdvance($user)))
            ]
        ];
    }
}
