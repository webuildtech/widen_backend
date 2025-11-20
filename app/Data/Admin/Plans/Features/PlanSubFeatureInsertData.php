<?php

namespace App\Data\Admin\Plans\Features;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanSubFeatureInsertData extends Data
{
    public function __construct(
        #[Exists('plan_features', 'id', withoutTrashed: true)]
        public ?int   $id,

        #[Rule(['max:255'])]
        public string $label,
    )
    {
    }
}
