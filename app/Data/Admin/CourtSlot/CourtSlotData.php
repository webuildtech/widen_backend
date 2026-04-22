<?php

namespace App\Data\Admin\CourtSlot;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtSlotData extends Data
{
    public function __construct(
        public int    $court_id,

        public string $date,

        public string $start_time,

        public string $end_time,

        public string|Optional $type,
    )
    {
    }
}
