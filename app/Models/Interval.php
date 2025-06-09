<?php

namespace App\Models;

use App\Models\Concerns\HasDateRangeScopes;
use App\Models\Concerns\HasIntervalScopes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperInterval
 */
class Interval extends BaseModel
{
    use HasIntervalScopes;
    use HasDateRangeScopes;

    protected $casts = [
        'date_from' => 'datetime',
        'date_to' => 'datetime',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(IntervalPrice::class);
    }

    public function courts(): BelongsToMany
    {
        return $this->belongsToMany(Court::class)->orderByPivot('order');
    }
}
