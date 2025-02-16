<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Plan extends BaseModel
{
    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'name',
            'type',
        ], 'like', "%$text%");
    }
}
