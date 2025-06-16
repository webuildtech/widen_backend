<?php

namespace App\Data\User\DiscountCodes;

use App\Enums\DiscountCodeType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiscountCodeData extends Data
{
    public function __construct(
        public string           $code,

        public DiscountCodeType $type,

        public float            $value,
    )
    {
    }
}
