<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperIntervalPrice
 */
class IntervalPrice extends BaseModel
{
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->withPivot(['price']);
    }
}
