<?php

namespace App\Data\Admin\Admins;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdminSelectOptionData extends Data
{
    public function __construct(
        public int    $id,

        public string $full_name,
    )
    {
    }
}
