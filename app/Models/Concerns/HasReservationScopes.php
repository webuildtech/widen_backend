<?php

namespace App\Models\Concerns;

use App\Models\Guest;
use App\Models\User;
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

    public function scopeOwnerAttributeLike(Builder $query, string $column, string $value): Builder
    {
        return $query->whereHasMorph(
            'owner',
            [User::class, Guest::class],
            fn(Builder $ownerQuery) => $ownerQuery->where($column, 'like', "%$value%")
        );
    }

    public function scopeOwnerFirstName(Builder $query, string $value): Builder
    {
        return $query->ownerAttributeLike('first_name', $value);
    }

    public function scopeOwnerLastName(Builder $query, string $value): Builder
    {
        return $query->ownerAttributeLike('last_name', $value);
    }

    public function scopeOwnerEmail(Builder $query, string $value): Builder
    {
        return $query->ownerAttributeLike('email', $value);
    }

    public function scopeOwnerPhone(Builder $query, string $value): Builder
    {
        return $query->ownerAttributeLike('phone', $value);
    }
}
