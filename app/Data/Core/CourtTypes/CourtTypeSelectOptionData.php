<?php

namespace App\Data\Core\CourtTypes;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtTypeSelectOptionData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,
    )
    {
    }
}
