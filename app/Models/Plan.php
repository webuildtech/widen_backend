<?php

namespace App\Models;

use App\Models\Concerns\HasPlanScopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Plan as PlanBase;

/**
 * @mixin IdeHelperPlan
 */
class Plan extends PlanBase
{
    use HasPlanScopes;

    protected $fillable = [
        'grace_days',
        'name',
        'type',
        'active',
        'price',
        'cancel_before',
        'periodicity_type',
        'periodicity',
    ];

    public function calculateNextRecurrenceEnd(Carbon|string $start = null): Carbon
    {
        if (empty($start)) {
            $start = now();
        }

        if (is_string($start)) {
            $start = Carbon::parse($start);
        }

        $recurrences = max(PeriodicityType::getDateDifference(from: $start, to: now(), unit: $this->periodicity_type), 0);
        $expirationDate = $start->copy()->add($this->periodicity_type, $this->periodicity + $recurrences)->endOfDay();

        return $expirationDate;
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }
}
