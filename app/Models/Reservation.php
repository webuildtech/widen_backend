<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperReservation
 */
class Reservation extends BaseModel
{
    protected $casts = [
        'is_paid' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'canceled_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function reservationGroup(): BelongsTo
    {
        return $this->belongsTo(ReservationGroup::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(ReservationSlot::class);
    }

    public function scopePaidAtBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('paid_at', $start, $end);
    }

    public function scopeCanceledAtBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('canceled_at', $start, $end);
    }
}
