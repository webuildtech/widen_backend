<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LoginData extends Data
{
    public function __construct(
        #[Email, Max(255)]
        public string $email,

        public string $password,

        public bool|Optional   $remember,
    )
    {
    }
}
