<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasAdminScopes
{
    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'email',
            'first_name',
            'last_name',
            'phone',
            'updated_at'
        ], 'like', "%$text%");
    }
}
