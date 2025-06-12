<?php

namespace App\Data\User\Plans;

use App\Data\User\PlanCourtTypeRules\PlanCourtTypeRuleData;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanData extends Data
{
    public function __construct(
        public int        $id,

        public string     $name,

        public string     $type,

        public float      $price,

        /** @var Collection<int, PlanCourtTypeRuleData> */
        public Collection $courtTypeRules
    )
    {
    }
}
