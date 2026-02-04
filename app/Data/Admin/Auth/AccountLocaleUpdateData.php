<?php

namespace App\Data\Admin\Auth;

use App\Enums\Locale;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountLocaleUpdateData extends Data
{
    public function __construct(
        public Locale $locale,
    )
    {
    }
}
