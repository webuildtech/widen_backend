<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperReservationSlot
 */
class ReservationSlot extends BaseModel
{
    protected $casts = [
        'slot_start' => 'datetime',
        'slot_end' => 'datetime',
        'try_sell' => 'boolean',
        'is_refunded' => 'boolean',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function scopeActive($query)
    {
        return $query->where('try_sell', false)->where('is_refunded', false);
    }
}
