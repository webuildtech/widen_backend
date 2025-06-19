<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin IdeHelperGuest
 */
class Guest extends BaseModel
{
    public function reservations(): MorphMany
    {
        return $this->morphMany(Reservation::class, 'owner');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'owner');
    }

    public function invoices(): MorphMany
    {
        return $this->morphMany(Invoice::class, 'owner');
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn () => trim("{$this->first_name} {$this->last_name}"));
    }
}
