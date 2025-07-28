<?php

namespace App\Data\User\Newsletter;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class NewsletterSubscribeData extends Data
{
    public function __construct(
        #[Email, Max(255)]
        public string $email,
    )
    {
    }
}
