<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum GameParticipantStatus: string
{
    case PENDING = 'pending';

    case CONFIRMED = 'confirmed';

    case CANCELED = 'canceled';
}
