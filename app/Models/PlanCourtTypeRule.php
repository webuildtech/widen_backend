<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperPlanCourtTypeRule
 */
class PlanCourtTypeRule extends BaseModel
{
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function courtType(): BelongsTo
    {
        return $this->belongsTo(CourtType::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(PlanCourtTypeRuleSlot::class);
    }
}
