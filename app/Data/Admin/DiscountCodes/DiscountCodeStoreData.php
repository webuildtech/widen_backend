<?php

namespace App\Data\Admin\DiscountCodes;

use App\Models\DiscountCode;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiscountCodeStoreData extends Data
{
    public function __construct(
        #[Max(255)]
        public string               $name,

        #[Unique(DiscountCode::class, column: 'code'), Max(255)]
        public string               $code,

        #[Numeric, Min(0), Max(100)]
        public float                $value,

        #[Rule(['min:0', 'max:2147483647'])]
        public int|Optional|null    $usage_limit,

        #[Date]
        public Carbon|Optional|null $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon|Optional|null $date_to,

        public bool|Optional        $is_active,
    )
    {
    }
}
