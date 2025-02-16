<?php

namespace App\Data\Admin\Users;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SelectUserData extends Data
{
    public function __construct(
        public int    $id,

        public string $full_name,
    )
    {
    }
}
