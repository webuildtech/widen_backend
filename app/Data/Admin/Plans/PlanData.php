<?php

namespace App\Data\Admin\Plans;

use App\Data\Admin\Plans\Features\PlanFeatureData;
use App\Data\Admin\Plans\Prices\PlanPriceData;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,

        public string $type,

        public bool   $is_active,

        public bool   $is_popular,

        public bool   $is_default,

        /** @var array<int, PlanFeatureData> */
        public array  $features,

        /** @var array<int, PlanPriceData> */
        public array  $prices
    )
    {
    }
}
