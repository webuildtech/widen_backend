<?php

namespace App\Data\User\Plans;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,

        public string $type,

        public int    $reservations_per_week,

        public float  $price,
    )
    {
    }
}
