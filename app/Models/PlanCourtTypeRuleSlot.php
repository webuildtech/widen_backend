<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPlanCourtTypeRuleSlot
 */
class PlanCourtTypeRuleSlot extends BaseModel
{
    public function planCourtTypeRule(): BelongsTo
    {
        return $this->belongsTo(PlanCourtTypeRule::class);
    }
}
