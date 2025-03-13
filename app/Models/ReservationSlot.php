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
    ];

    public function reservationTime(): BelongsTo
    {
        return $this->belongsTo(ReservationTime::class);
    }
}
