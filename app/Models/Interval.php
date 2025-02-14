<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperInterval
 */
class Interval extends BaseModel
{
    public function prices(): HasMany
    {
        return $this->hasMany(IntervalPrice::class);
    }

    protected $casts = [
        'date_from' => 'datetime',
        'date_to' => 'datetime',
    ];

    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'name',
            'inside_name',
            'date_from',
            'date_to',
        ], 'like', "%$text%");
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
