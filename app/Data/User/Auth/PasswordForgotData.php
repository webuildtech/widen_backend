<?php

namespace App\Data\User\Auth;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PasswordForgotData extends Data
{
    public function __construct(
        #[Email, Max(255)]
        public string $email,
    )
    {
    }
}
