<?php

namespace App\Data\User\Auth;

use App\Enums\Social;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SocialData extends Data
{
    public function __construct(
        public string $accessToken,

        public Social $social,
    )
    {
    }
}
