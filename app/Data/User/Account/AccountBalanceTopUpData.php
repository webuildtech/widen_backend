<?php

namespace App\Data\User\Account;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountBalanceTopUpData extends Data
{
    public function __construct(
        #[Min(1), Max(10000)]
        public float $amount,
    )
    {
    }
}
