<?php

namespace App\Data\Admin\Plans;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanListData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,

        public string $type,

        public float  $price,

        public bool   $active,

        public Carbon $updated_at,
    )
    {
    }
}
