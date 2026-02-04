<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum Locale: string
{
    case LT = 'lt';

    case EN = 'en';
}
