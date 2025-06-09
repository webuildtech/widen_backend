<?php

namespace App\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasDateRangeScopes
{
    public function scopeDateFromBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('date_from', $start, $end);
    }

    public function scopeDateToBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('date_to', $start, $end);
    }
}
