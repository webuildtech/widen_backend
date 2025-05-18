<?php

namespace App\Data\Admin\Courts;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SelectCourtData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,
    )
    {
    }
}
