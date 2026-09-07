<?php

namespace App\Models;

use App\Enums\Day;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDiscountCodeWindow
 */
class DiscountCodeWindow extends BaseModel
{
    protected $casts = [
        'day' => Day::class,
    ];

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function covers(string $time): bool
    {
        return $time >= $this->start_time && $time < $this->end_time;
    }
}
