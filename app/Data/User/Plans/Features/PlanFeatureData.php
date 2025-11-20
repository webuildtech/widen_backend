<?php

namespace App\Data\User\Plans\Features;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanFeatureData extends Data
{
    public function __construct(
        public string $label,

        /** @var array<int, PlanSubFeatureData> */
        public array $subFeatures
    )
    {
    }
}
