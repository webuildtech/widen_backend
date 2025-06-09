<?php

namespace App\Data\Core\Pricing;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PriceDetailsData extends Data
{
    public function __construct(
        public float $price,

        public float $discount,

        public float $vat,

        public float $price_with_vat,
    )
    {
        $this->price = round($this->price, 2);
        $this->discount = round($this->discount, 2);
        $this->vat = round($this->vat, 2);
        $this->price_with_vat = round($this->price_with_vat, 2);
    }
}
