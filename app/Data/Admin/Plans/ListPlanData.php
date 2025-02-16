<?php

namespace App\Data\Admin\Plans;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ListPlanData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,

        public string $type,

        public int    $reservations_per_week,

        public int    $cancel_before,

        public float  $price,

        public bool   $active,

        public Carbon $updated_at,
    )
    {
    }
}
