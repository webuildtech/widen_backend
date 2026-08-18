<?php

namespace App\Data\Core\Games;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameLevelSelectOptionData extends Data
{
    public function __construct(
        public int     $id,

        public string  $name,

        public ?string $description,

        public int     $sort_order,
    )
    {
    }
}
