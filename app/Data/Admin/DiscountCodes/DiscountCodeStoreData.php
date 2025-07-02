<?php

namespace App\Data\Admin\DiscountCodes;

use App\Enums\DiscountCodeType;
use App\Models\DiscountCode;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class DiscountCodeStoreData extends Data
{
    public function __construct(
        #[Max(255)]
        public string               $name,

        #[Unique(DiscountCode::class, column: 'code'), Max(255)]
        public string               $code,

        public DiscountCodeType     $type,

        #[Numeric, Min(0)]
        public float                $value,

        #[Min(0), Max(2147483647)]
        public int|Optional|null    $usage_limit,

        #[Date]
        public Carbon|Optional|null $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon|Optional|null $date_to,

        public bool|Optional        $is_active,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'value' => [Rule::when(request('type') === DiscountCodeType::PERCENT->value, ['max:100'])],
        ];
    }
}
