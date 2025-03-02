<?php

namespace App\Models;

use App\Observers\ReservationTimeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//tut nijasna
//#[ObservedBy([ReservationTimeObserver::class])]
class ReservationTime extends BaseModel
{
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
