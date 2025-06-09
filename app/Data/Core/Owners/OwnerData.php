<?php

namespace App\Data\Core\Owners;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class OwnerData extends Data
{
    public function __construct(
        public string $full_name,

        public string $email,

        public string $phone,
    )
    {
    }
}
