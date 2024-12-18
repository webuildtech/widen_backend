<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountData extends Data
{
    public function __construct(
        public string  $email,
        public string  $first_name,
        public ?string $last_name,
        public string  $updated_at
    )
    {
    }
}
