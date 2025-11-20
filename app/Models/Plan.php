<?php

namespace App\Models;

use App\Models\Concerns\HasPlanScopes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @mixin IdeHelperPlan
 */
class Plan extends BaseModel
{
    use HasPlanScopes;

    protected $guarded = [];

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function courtTypeRules(): HasMany
    {
        return $this->hasMany(PlanCourtTypeRule::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class)->whereNull('parent_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(PlanPrice::class);
    }
}
