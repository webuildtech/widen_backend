<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasPlanScopes
{
    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'name',
            'type',
        ], 'like', "%$text%");
    }
}
