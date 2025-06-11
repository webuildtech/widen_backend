<?php

namespace App\Data\Admin\PlanCourtTypeRules;

use App\Enums\Day;
use App\Support\RegexPatterns;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanCourtTypeRuleSlotData extends Data
{
    public function __construct(
        public Day    $day,

        #[Regex(RegexPatterns::TIME_HALF_HOUR)]
        public string $start_time,

        #[Regex(RegexPatterns::TIME_HALF_HOUR)]
        public string $end_time,
    )
    {
    }
}
