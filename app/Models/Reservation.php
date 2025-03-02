<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends BaseModel
{
    public function times(): HasMany
    {
        return $this->hasMany(ReservationTime::class);
    }

    // tut nijasna
    public function updateTotalPrice(): void
    {
        $this->total_price = $this->times()->sum('price');
        $this->save();
    }
}
