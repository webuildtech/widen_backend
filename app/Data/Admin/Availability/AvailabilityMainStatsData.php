<?php

namespace App\Data\Admin\Availability;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AvailabilityMainStatsData extends Data
{
    public function __construct(
        public AvailabilityStatsWithCourtTypesData $yesterday,

        public AvailabilityStatsWithCourtTypesData $today,

        public AvailabilityStatsWithCourtTypesData $tomorrow,

        public AvailabilityMonthsStatsData $months,
    )
    {
    }
}
