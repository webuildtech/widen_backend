<?php

namespace App\Data\Admin\Games;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameCourtConflictData extends Data
{
    public function __construct(
        public int        $court_id,

        public string     $court_name,

        public string     $date,

        /** @var Collection<int, GameSlotConflictData> */
        public Collection $slots,
    )
    {
    }
}
