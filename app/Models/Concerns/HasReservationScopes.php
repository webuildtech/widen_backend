<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasReservationScopes
{
    public function scopePaidAtBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('paid_at', $start, $end);
    }

    public function scopeCanceledAtBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('canceled_at', $start, $end);
    }
}
