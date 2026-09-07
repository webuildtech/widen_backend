<?php

namespace App\Data\User\GameGroups;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameGroupSelectOptionData extends Data
{
    public function __construct(
        public string $uuid,

        public string $name,
    )
    {
    }
}
