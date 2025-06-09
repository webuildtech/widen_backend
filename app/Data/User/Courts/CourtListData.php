<?php

namespace App\Data\User\Courts;

use App\Data\Core\Media\MediaData;
use App\Enums\CourtType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtListData extends Data
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
