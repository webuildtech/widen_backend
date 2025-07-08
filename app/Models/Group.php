<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperGroup
 */
class Group extends BaseModel
{
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function intervalPrices(): BelongsToMany
    {
        return $this->belongsToMany(IntervalPrice::class)->withPivot(['price']);
    }

    public function usersIds(): Attribute
    {
        return Attribute::get(fn() => $this->users->pluck('id')->toArray());
    }
}
