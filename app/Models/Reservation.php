<?php

namespace App\Models;

use App\Models\Concerns\HasReservationScopes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperReservation
 */
class Reservation extends BaseModel
{
    use HasReservationScopes;

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
}
