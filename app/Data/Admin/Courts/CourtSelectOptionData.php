<?php

namespace App\Data\Admin\Courts;

use App\Enums\CourtType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtSelectOptionData extends Data
{
    public function __construct(
        public int       $id,

        public string    $name,

        public CourtType $type,
    )
    {
    }
}
