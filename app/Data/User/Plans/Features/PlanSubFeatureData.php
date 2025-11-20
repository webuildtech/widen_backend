<?php

namespace App\Data\User\Plans\Features;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanSubFeatureData extends Data
{
    public function __construct(
        public string $label,
    )
    {
    }
}
