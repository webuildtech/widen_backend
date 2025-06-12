<?php

namespace App\Data\User\PlanCourtTypeRules;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanCourtTypeRuleData extends Data
{
    public function __construct(
        public int $court_type_id,

        public int $max_days_in_advance,

        public int $cancel_hours_before,
    )
    {
    }
}
