<?php

namespace App\Data\Admin\Plans\Features;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanFeatureInsertData extends Data
{
    public function __construct(
        #[Exists('plan_features', 'id', withoutTrashed: true)]
        public ?int           $id,

        #[Rule(['max:255'])]
        public string         $label,

        /** @var array<int, PlanSubFeatureInsertData> */
        public array|Optional $subFeatures
    )
    {
    }
}
