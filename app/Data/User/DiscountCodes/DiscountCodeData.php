<?php

namespace App\Data\User\DiscountCodes;

use App\Data\Core\DiscountCodes\DiscountCodeWindowData;
use App\Enums\DiscountCodeType;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiscountCodeData extends Data
{
    public function __construct(
        public string           $code,

        public DiscountCodeType $type,

        public float            $value,

        /** @var array<int> */
        public array            $court_types_ids,

        /** @var Collection<int, DiscountCodeWindowData> */
        #[LoadRelation]
        public Collection       $windows,
    )
    {
    }
}
