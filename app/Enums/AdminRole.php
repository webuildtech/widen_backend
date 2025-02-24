<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum AdminRole: string
{
    case SUPER_ADMIN = 'superAdmin';

    case EMPLOYEE = 'employee';
}

