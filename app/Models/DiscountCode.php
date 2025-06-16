<?php

namespace App\Models;

use App\Enums\DiscountCodeType;
use App\Models\Concerns\HasDateRangeScopes;

/**
 * @mixin IdeHelperDiscountCode
 */
class DiscountCode extends BaseModel
{
    use HasDateRangeScopes;

    protected $casts = [
        'value' => 'decimal:2',
        'type' => DiscountCodeType::class,
    ];
}
