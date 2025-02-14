<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum Day: string
{
    case MONDAY = 'mon';

    case TUESDAY = 'tue';

    case WEDNESDAY = 'wed';

    case THURSDAY = 'thu';

    case FRIDAY = 'fri';

    case SATURDAY = 'sat';

    case SUNDAY = 'sun';
}
