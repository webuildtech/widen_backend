<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum CourtType: string
{
    case TENNIS = 'tennis';

    case TABLE_TENNIS = 'table_tennis';
}
