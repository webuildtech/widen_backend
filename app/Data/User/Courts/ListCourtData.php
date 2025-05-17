<?php

namespace App\Data\User\Courts;

use App\Data\MediaData;
use App\Enums\CourtType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ListCourtData extends Data
{
    public function __construct(
        public int        $id,

        public string     $name,

        public ?string    $description,

        /** @var array<int, CourtSlotData> */
        public array      $slots,

        public CourtType  $type,

        public ?MediaData $logo,
    )
    {
    }
}
