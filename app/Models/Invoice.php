<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperInvoice
 */
class Invoice extends BaseModel
{
    public function owner(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function scopeInvoiceDateBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('date', $start, $end);
    }
}
