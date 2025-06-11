<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCourtType
 */
class CourtType extends BaseModel
{
    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }

    public function planRules(): HasMany
    {
        return $this->hasMany(PlanCourtTypeRule::class);
    }
}
