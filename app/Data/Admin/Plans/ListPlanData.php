<?php

namespace App\Data\Admin\Plans;

use App\Models\Plan;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ListPlanData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,

        public string $type,

        public float  $price,

        public bool   $active,

        public Carbon $updated_at,
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
            $plan->active,
            $plan->updated_at,
        );
    }
}
