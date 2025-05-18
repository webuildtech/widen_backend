<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDowntime
 */
class Downtime extends BaseModel
{
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function scopeDateBetween(Builder $query, ...$interval): Builder
    {
        $table = $this->getTable();

        $query->whereDate("$table.date_from", '<=', Carbon::parseWithAppTimezone($interval[0]));

        if (!empty($interval[1])) {
            $query->whereDate("$table.date_to", '>=', Carbon::parseWithAppTimezone($interval[1]));
        }

        return $query;
    }
}
