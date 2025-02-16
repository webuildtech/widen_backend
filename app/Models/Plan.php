<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends BaseModel
{
    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'name',
            'type',
        ], 'like', "%$text%");
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
