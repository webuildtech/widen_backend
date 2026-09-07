<?php

namespace App\Data\Admin\DiscountCodes;

use App\Data\Core\DiscountCodes\DiscountCodeWindowData;
use App\Enums\DiscountCodeType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\LoadRelation;
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

        /** @var array<int> */
        public array            $court_types_ids,

        /** @var Collection<int, DiscountCodeWindowData> */
        #[LoadRelation]
        public Collection       $windows,

        public Carbon           $updated_at,
    )
    {
    }
}
