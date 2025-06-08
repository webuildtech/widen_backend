<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperReservationGroup
 */
class ReservationGroup extends BaseModel
{
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
