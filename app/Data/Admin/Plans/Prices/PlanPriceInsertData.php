<?php

namespace App\Data\Admin\Plans\Prices;

use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanPriceInsertData extends Data
{
    public function __construct(
        #[Exists('plan_prices', 'id', withoutTrashed: true)]
        public ?int   $id,

        #[In(PeriodicityType::Year, PeriodicityType::Month)]
        public string $periodicity_type,

        #[Numeric, Min(0)]
        public ?float $previous_price,

        #[Numeric, Min(0)]
        public float  $price,
    )
    {
    }
}
