<?php

namespace App\Data\Admin\PlanCourtTypeRules;

use App\Data\Core\CourtTypes\CourtTypeSelectOptionData;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanCourtTypeRuleData extends Data
{
    public function __construct(
        public int                       $id,

        #[LoadRelation]
        public CourtTypeSelectOptionData $courtType,

        public int                       $max_days_in_advance,

        public int                       $cancel_hours_before,

        /** @var Collection<int, PlanCourtTypeRuleSlotData> */
        #[LoadRelation]
        public Collection                $slots,
    )
    {
    }
}
