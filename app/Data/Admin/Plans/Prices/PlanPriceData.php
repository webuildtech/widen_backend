<?php

namespace App\Data\Admin\Plans\Prices;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanPriceData extends Data
{
    public function __construct(
        public int    $id,

        public string $periodicity_type,

        public ?float $previous_price,

        public float  $price,
    )
    {
    }
}
