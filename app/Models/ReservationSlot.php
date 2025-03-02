<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationSlot extends BaseModel
{
    public function reservationTime(): BelongsTo
    {
        return $this->belongsTo(ReservationTime::class);
    }
}
