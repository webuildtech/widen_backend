<?php

namespace App\Data\Admin\GameGroups;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameGroupSelectOptionData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,
    )
    {
    }
}
