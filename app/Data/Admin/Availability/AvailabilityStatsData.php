<?php

namespace App\Data\Admin\Availability;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AvailabilityStatsData extends Data
{
    public function __construct(
        public int $total,

        public int $reserved,
        public float $reserved_pct,

        public int $blocked,
        public float $blocked_pct,

        public int $free,
        public float $free_pct,

        public int $occupied,
        public float $occupied_pct,

        /** @var array<int, AvailabilityReservedByTypeData> */
        public array $reserved_by_type,
    )
    {
    }
}
