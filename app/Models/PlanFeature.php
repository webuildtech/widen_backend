<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperPlanFeature
 */
class PlanFeature extends Model
{
    protected $guarded = [];

    public function subFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class, 'parent_id');
    }
}
