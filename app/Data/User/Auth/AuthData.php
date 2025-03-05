<?php

namespace App\Data\User\Auth;

use App\Data\User\AccountData;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AuthData extends Data
{
    public function __construct(
        public AccountData $authUser,
        public string      $accessToken
    )
    {
    }
}
