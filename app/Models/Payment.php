<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperPayment
 */
class Payment extends BaseModel
{
    protected $casts = [
        'renew' => 'boolean',
        'paid_at' => 'datetime',
        'price' => 'decimal:2',
        'vat' => 'decimal:2',
        'price_with_vat' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'paid_amount_from_balance' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'refunded_amount_to_balance' => 'decimal:2',
        'status' => PaymentStatus::class,
    ];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function scopePaidAtBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('paid_at', $start, $end);
    }
}
