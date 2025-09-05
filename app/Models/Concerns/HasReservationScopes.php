<?php

namespace App\Models\Concerns;

use Carbon\Carbon;
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

    public function scopeStartTimeFrom(Builder $query, string $start): Builder
    {
        return $query->whereTime('start_time', '>=',  Carbon::parseWithAppTimezone($start)->setSecond(0));
    }

    public function scopeEndTimeTo(Builder $query, string $end): Builder
    {
        return $query->whereTime('end_time', '<=', Carbon::parseWithAppTimezone($end)->setSecond(0));
    }
}
