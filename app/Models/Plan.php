<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LucasDotVin\Soulbscription\Models\Plan as PlanBase;

/**
 * @mixin IdeHelperPlan
 */
class Plan extends PlanBase
{
    protected $fillable = [
        'grace_days',
        'name',
        'type',
        'active',
        'price',
        'periodicity_type',
        'periodicity',
    ];

    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'name',
            'type',
        ], 'like', "%$text%");
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
