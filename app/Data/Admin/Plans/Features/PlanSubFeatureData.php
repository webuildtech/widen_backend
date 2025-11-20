<?php

namespace App\Data\Admin\Plans\Features;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanSubFeatureData extends Data
{
    public function __construct(
        public int    $id,

        public string $label,
    )
    {
    }
}
