<?php

namespace App\Data\User\Subscriptions;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SubscribeData extends Data
{
    public function __construct(
        public string|Optional|null $discount_code,
    )
    {
    }
}
