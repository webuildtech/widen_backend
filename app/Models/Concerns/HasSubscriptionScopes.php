<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasSubscriptionScopes
{
    public function scopeVisible(Builder $query): Builder
    {
        return $query
            ->whereNull('deleted_at')
            ->where(fn(Builder $query) => $query
                ->whereNull('suppressed_at')
                ->orWhereNotNull('canceled_at'));
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return match ($status) {
            'canceled' => $query->whereNotNull('canceled_at'),
            'expired' => $query->whereNull('canceled_at')->where(fn(Builder $query) => $query
                ->where('expired_at', '<', now())
                ->where(fn(Builder $query) => $query
                    ->whereNull('grace_days_ended_at')
                    ->orWhere('grace_days_ended_at', '<', now()))),
            'active' => $query->whereNull('canceled_at')->where(fn(Builder $query) => $query
                ->where('expired_at', '>=', now())
                ->orWhere('grace_days_ended_at', '>=', now())),
            default => $query,
        };
    }

    public function scopeStartedAtBetween(Builder $query, ?string $start = null, ?string $end = null): Builder
    {
        return $this->rangeBetween($query, 'started_at', $start, $end);
    }

    public function scopeExpiredAtBetween(Builder $query, ?string $start = null, ?string $end = null): Builder
    {
        return $this->rangeBetween($query, 'expired_at', $start, $end);
    }

    public function scopeCanceledAtBetween(Builder $query, ?string $start = null, ?string $end = null): Builder
    {
        return $this->rangeBetween($query, 'canceled_at', $start, $end);
    }

    public function scopeSubscriberFirstName(Builder $query, string $value): Builder
    {
        return $this->whereSubscriber($query, 'first_name', $value);
    }

    public function scopeSubscriberLastName(Builder $query, string $value): Builder
    {
        return $this->whereSubscriber($query, 'last_name', $value);
    }

    public function scopeSubscriberEmail(Builder $query, string $value): Builder
    {
        return $this->whereSubscriber($query, 'email', $value);
    }

    public function scopeSubscriberPhone(Builder $query, string $value): Builder
    {
        return $this->whereSubscriber($query, 'phone', $value);
    }

    private function rangeBetween(Builder $query, string $column, ?string $start, ?string $end): Builder
    {
        return $start ? $query->dateBetween($column, $start, $end ?: null) : $query;
    }

    private function whereSubscriber(Builder $query, string $column, string $value): Builder
    {
        return $query->whereHasMorph(
            'subscriber',
            User::class,
            fn(Builder $query) => $query->where($column, 'like', "%$value%")
        );
    }
}
