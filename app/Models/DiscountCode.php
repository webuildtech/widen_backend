<?php

namespace App\Models;

use App\Models\Concerns\HasDateRangeScopes;

/**
 * @mixin IdeHelperDiscountCode
 */
class DiscountCode extends BaseModel
{
    use HasDateRangeScopes;
}
