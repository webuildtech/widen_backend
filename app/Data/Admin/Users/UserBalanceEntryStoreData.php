<?php

namespace App\Data\Admin\Users;

use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserBalanceEntryStoreData extends Data
{
    public function __construct(
        #[Numeric, Rule('decimal:0,2'), Between(0.01, 999999.99)]
        public float  $amount,

        #[Max(255)]
        public ?string $reason = null,
    )
    {
    }
}
