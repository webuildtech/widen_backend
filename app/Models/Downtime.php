<?php

namespace App\Models;

use App\Models\Concerns\HasDateRangeScopes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDowntime
 */
class Downtime extends BaseModel
{
    use HasDateRangeScopes;

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }
}
