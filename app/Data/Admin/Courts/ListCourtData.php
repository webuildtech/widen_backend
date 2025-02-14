<?php

namespace App\Data\Admin\Courts;


use App\Data\MediaData;
use App\Enums\CourtType;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ListCourtData extends Data
{
    public function __construct(
        public int        $id,

        public string     $name,

        public string     $inside_name,

        public bool       $active,

        public CourtType  $type,

        public ?MediaData $logo,

        public Carbon     $updated_at,
    )
    {
    }
}
