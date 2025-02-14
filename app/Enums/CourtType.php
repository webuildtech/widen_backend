<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum CourtType: string
{
    case INDOOR = 'indoor';

    case OUTDOOR = 'outdoor';
}
