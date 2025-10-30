<?php

namespace App\Data\Admin\Availability;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AvailabilityMonthsStatsData extends Data
{
    public function __construct(
        /** @var array<string> */
        public array $labels,

        /** @var array<int, AvailabilityStatsWithCourtTypesData> */
        public array $data,
    )
    {
    }
}
