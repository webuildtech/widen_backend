<?php

namespace App\Data\Admin\Plans;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,

        public string $type,

        public int    $cancel_before,

        public float  $price,

        public bool   $active,
    )
    {
    }
}
