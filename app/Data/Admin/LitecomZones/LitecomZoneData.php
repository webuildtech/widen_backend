<?php

namespace App\Data\Admin\LitecomZones;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LitecomZoneData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,

        public string $auto_scene,

        public int    $auto_turn_on_before,

        public int    $auto_turn_off_after,
    )
    {
    }
}
