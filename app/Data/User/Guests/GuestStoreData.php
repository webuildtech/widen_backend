<?php

namespace App\Data\User\Guests;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GuestStoreData extends Data
{
    public function __construct(
        #[Email, Max(255)]
        public string $email,

        #[Max(255)]
        public string $first_name,

        #[Max(255)]
        public string $last_name,

        #[Max(255)]
        public string $phone,
    )
    {
    }
}
