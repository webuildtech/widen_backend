<?php

namespace App\Data\Admin\DiscountCodes;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiscountCodeData extends Data
{
    public function __construct(
        public int     $id,

        public string  $name,

        public string  $code,

        public bool    $is_active,

        public float   $value,

        public ?int    $usage_limit,

        public ?Carbon $date_from,

        public ?Carbon $date_to,
    )
    {
    }
}
