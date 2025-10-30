<?php

namespace App\Data\Admin\Availability;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AvailabilityStatsWithCourtTypesData extends Data
{
    public function __construct(
        public AvailabilityStatsData $overall,

        /** @var array<int, AvailabilityStatsData> */
        public ?array $by_court_type
    )
    {
    }
}
