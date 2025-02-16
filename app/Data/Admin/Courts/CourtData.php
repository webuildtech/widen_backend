<?php

namespace App\Data\Admin\Courts;

use App\Data\MediaData;
use App\Enums\CourtType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtData extends Data
{
    public function __construct(
        public int        $id,

        public string     $name,

        public string     $inside_name,

        public ?string    $description,

        public bool       $active,

        public CourtType  $type,

        public ?MediaData $logo,

        /** @var array<int> */
        public array      $intervals_ids
    )
    {
    }
}
