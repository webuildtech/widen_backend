<?php

namespace App\Data\Admin\Plans\Features;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanFeatureData extends Data
{
    public function __construct(
        public int    $id,

        public string $label,

        /** @var array<int, PlanSubFeatureData> */
        public array  $subFeatures
    )
    {
    }
}
