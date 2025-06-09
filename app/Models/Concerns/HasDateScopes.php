<?php

namespace App\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasDateScopes
{
    public function scopeUpdatedAtBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('updated_at', $start, $end);
    }

    public function scopeDateBetween(Builder $query, string $column, string $start, ?string $end = null): Builder
    {
        $query->whereDate("{$this->getTable()}.$column", '>=', Carbon::parseWithAppTimezone($start));

        if ($end !== null) {
            $query->whereDate("{$this->getTable()}.$column", '<=', Carbon::parseWithAppTimezone($end));
        }

        return $query;
    }
}
