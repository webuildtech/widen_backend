<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasIntervalScopes
{
    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'name',
            'inside_name',
            'date_from',
            'date_to',
        ], 'like', "%$text%");
    }
}
