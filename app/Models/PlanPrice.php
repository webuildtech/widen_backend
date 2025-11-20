<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Plan as PlanBase;

/**
 * @mixin IdeHelperPlanPrice
 */
class PlanPrice extends PlanBase
{
    protected $fillable = [
        'plan_id',
        'grace_days',
        'price',
        'previous_price',
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
        \Log::info("Recurrences: $recurrences");
        $expirationDate = $start->copy()->add($this->periodicity_type, $this->periodicity + $recurrences)->endOfDay();

        return $expirationDate;
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
