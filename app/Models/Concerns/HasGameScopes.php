<?php

namespace App\Models\Concerns;

use App\Enums\GameStatus;
use Illuminate\Database\Eloquent\Builder;

trait HasGameScopes
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', GameStatus::PUBLISHED);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('end_time', '>', now());
    }

    public function scopeStartTimeFrom(Builder $query, string $start): Builder
    {
        return $query->where('start_time', '>=', $start);
    }

    public function scopeEndTimeTo(Builder $query, string $end): Builder
    {
        return $query->where('end_time', '<=', $end);
    }
}
