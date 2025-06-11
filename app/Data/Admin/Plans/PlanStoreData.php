<?php

namespace App\Data\Admin\Plans;

use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
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

        #[Numeric, Min(0)]
        public float         $price,

        public bool|Optional $is_active,
    )
    {
    }
}
