<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum GameStatus: string
{
    case PUBLISHED = 'published';

    case CANCELED = 'canceled';
}
