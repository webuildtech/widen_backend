<?php

namespace App\Data\User\Auth;

use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PasswordResetData extends Data
{
    public function __construct(
        public string $token,

        #[Email, Max(255)]
        public string $email,

        #[Min(6), Max(32), Confirmed]
        public string $password,
    )
    {
    }
}
