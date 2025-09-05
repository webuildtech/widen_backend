<?php

namespace App\Data\Admin\LitecomZones;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LitecomZoneUpdateData extends Data
{
    public function __construct(
        #[Max(255)]
        public string|Optional $name,

        #[In([1, 2, 3, 4])]
        public int|Optional    $auto_scene,

        #[Min(0)]
        public int|Optional    $auto_turn_on_before,

        #[Min(0)]
        public int|Optional    $auto_turn_off_after,
    )
    {
    }
}
