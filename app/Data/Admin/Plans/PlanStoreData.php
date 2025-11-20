<?php

namespace App\Data\Admin\Plans;

use App\Data\Admin\Plans\Features\PlanFeatureInsertData;
use App\Data\Admin\Plans\Prices\PlanPriceInsertData;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanStoreData extends Data
{
    public function __construct(
        #[Rule(['max:255'])]
        public string        $name,

        #[Rule(['max:255'])]
        public string        $type,

        public bool|Optional $is_active,

        public bool|Optional $is_popular,

        /** @var array<int, PlanFeatureInsertData> */
        public array         $features,

        /** @var array<int, PlanPriceInsertData> */
        public array         $prices
    )
    {
    }
}
