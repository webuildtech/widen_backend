<?php

namespace App\Data\User\Plans;

use App\Models\Plan;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,

        public string $type,

        public float  $price,

        public int    $cancel_before,
    )
    {
    }

    public static function fromModel(Plan $plan): self
    {
        return new self(
            $plan->id,
            $plan->name,
            $plan->type,
            $plan->price,
            $plan->cancel_before
        );
    }
}
