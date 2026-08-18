<?php

namespace App\Data\Admin\Games;

use App\Enums\GameConflictReason;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameSlotConflictData extends Data
{
    public function __construct(
        public string             $start_time,

        public string             $end_time,

        public GameConflictReason $reason,
    )
    {
    }
}
