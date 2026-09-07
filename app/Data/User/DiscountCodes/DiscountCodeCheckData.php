<?php

namespace App\Data\User\DiscountCodes;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiscountCodeCheckData extends Data
{
    public function __construct(
        #[Max(255)]
        public string      $code,

        /** @var Collection<int, DiscountCodeCheckLineData>|null */
        public ?Collection $lines,
    )
    {
    }
}
