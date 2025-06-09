<?php

namespace App\Data\Admin\Plans;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanSelectOptionData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,
    )
    {
    }
}
