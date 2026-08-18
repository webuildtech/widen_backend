<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum GameConflictReason: string
{
    case IN_PAST = 'in_past';

    case COURT_CLOSED = 'court_closed';

    case DOWNTIME = 'downtime';

    case RESERVED = 'reserved';
}
