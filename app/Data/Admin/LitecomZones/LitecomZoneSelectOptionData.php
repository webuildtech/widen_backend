<?php

namespace App\Data\Admin\LitecomZones;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LitecomZoneSelectOptionData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,
    )
    {
    }
}
