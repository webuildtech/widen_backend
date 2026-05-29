<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCourtType
 */
class CourtType extends BaseModel
{
    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('ordered', function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        });
    }

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }

    public function planRules(): HasMany
    {
        return $this->hasMany(PlanCourtTypeRule::class);
    }
}
