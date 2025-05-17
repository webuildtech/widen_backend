<?php

namespace App\Data\Admin\Plans;

use App\Enums\FeatureType;
use App\Models\Plan;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanData extends Data
{
    public function __construct(
        public int        $id,

        public string     $name,

        public string     $type,

        public int        $reservations_per_week,

        public int        $cancel_before,

        public float      $price,

        public bool       $active,
    )
    {
    }

    public static function fromModel(Plan $plan): self
    {
        return new self(
            $plan->id,
            $plan->name,
            $plan->type,
            $plan->features()->where('name', FeatureType::RESERVATION_PER_WEEK->value)->first()->pivot->charges,
            $plan->cancel_before,
            $plan->price,
            $plan->active,
        );
    }
}
