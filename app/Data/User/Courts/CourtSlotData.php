<?php

namespace App\Data\User\Courts;

use App\Enums\Day;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtSlotData extends Data
{
    public function __construct(
        public int    $court_id,

        public string $date,

        public Day    $day,

        public string $start_time,

        public string $end_time,

        public float  $price,
    )
    {
    }
}
