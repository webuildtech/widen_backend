<?php

namespace App\Data\Admin\CourtSlot;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtListData extends Data
{
    public function __construct(
        public int                       $id,

        public string                    $name,

        /** @var array<int, CourtSlotData> */
        public array                     $slots,
    )
    {
    }
}
