<?php

namespace App\Data\Admin\Plans;

use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanUpdateData extends Data
{
    public function __construct(
        #[Rule(['max:255'])]
        public string|Optional $name,

        #[Rule(['max:255'])]
        public string|Optional $type,

        #[Rule(['min:0', 'max:2147483647'])]
        public int|Optional    $cancel_before,

        #[Numeric, Min(0)]
        public float|Optional  $price,

        public bool|Optional   $active,
    )
    {
    }
}
