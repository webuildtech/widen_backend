<?php

namespace App\Data\User\Plans;

use App\Data\User\PlanCourtTypeRules\PlanCourtTypeRuleData;
use App\Data\User\Plans\Features\PlanFeatureData;
use App\Data\User\Plans\Prices\PlanPriceData;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanData extends Data
{
    public function __construct(
        public string     $name,

        public string     $type,

        public bool       $is_popular,

        /** @var Collection<int, PlanCourtTypeRuleData> */
        public Collection $courtTypeRules,

        /** @var array<int, PlanFeatureData> */
        public array      $features,

        /** @var array<int, PlanPriceData> */
        public array      $prices
    )
    {
    }
}
