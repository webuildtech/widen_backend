<?php

namespace App\Data\Admin\PlanCourtTypeRules;

use App\Data\Core\CourtTypes\CourtTypeSelectOptionData;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanCourtTypeRuleListData extends Data
{
    public function __construct(
        public int                       $id,

        public CourtTypeSelectOptionData $courtType,

        public int                       $max_days_in_advance,

        public int                       $cancel_hours_before,

        public Carbon                    $updated_at,
    )
    {
    }
}
