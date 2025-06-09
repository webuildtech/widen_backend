<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasUserScopes
{
    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'first_name',
            'last_name',
            'email',
            'birthday',
            'phone',
            'company_name',
            'company_code',
            'updated_at',
        ], 'like', "%$text%");
    }

    public function scopeBirthdayBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('birthday', $start, $end);
    }
}
