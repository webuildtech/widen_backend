<?php

namespace App\Data\Admin\DiscountCodes;

use App\Enums\DiscountCodeType;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiscountCodeListData extends Data
{
    public function __construct(
        public int              $id,

        public string           $name,

        public string           $code,

        public bool             $is_active,

        public DiscountCodeType $type,

        public float            $value,

        public ?int             $usage_limit,

        public int              $used,

        public ?string          $date_from,

        public ?string          $date_to,

        public Carbon           $updated_at,
    )
    {
    }
}
