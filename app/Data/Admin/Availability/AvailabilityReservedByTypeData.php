<?php

namespace App\Data\Admin\Availability;

use App\Enums\AvailabilitySlotType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AvailabilityReservedByTypeData extends Data
{
    public function __construct(
        public AvailabilitySlotType $type,

        public int                  $count,

        public float                $pct,
    )
    {
    }
}
