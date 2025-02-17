<?php

namespace App\Data\Admin\Groups;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SelectGroupData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,
    )
    {
    }
}
