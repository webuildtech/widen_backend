<?php

namespace App\Data\Admin\PlanCourtTypeRules;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanCourtTypeRuleUpdateData extends Data
{
    public function __construct(
        #[Rule(['min:0', 'max:2147483647'])]
        public int|Optional        $max_days_in_advance,

        #[Rule(['min:0', 'max:2147483647'])]
        public int|Optional        $cancel_hours_before,

        /** @var Collection<int, PlanCourtTypeRuleSlotData> */
        public Collection|Optional $slots,
    )
    {
    }
}
