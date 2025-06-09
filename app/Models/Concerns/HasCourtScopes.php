<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasCourtScopes
{
    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'name',
            'inside_name',
        ], 'like', "%$text%");
    }
}
