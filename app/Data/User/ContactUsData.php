<?php

namespace App\Data\User;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ContactUsData extends Data
{
    public function __construct(
        #[Max(255)]
        public string $name,

        #[Email, Max(255)]
        public string $email,

        #[Max(255)]
        public string $phone,

        public string $message,
    )
    {
    }
}
