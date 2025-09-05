<?php

namespace App\Data\Admin\LitecomZones;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LitecomZoneListData extends Data
{
    public function __construct(
        public int     $id,

        public string  $name,

        public int     $auto_scene,

        public int     $auto_turn_on_before,

        public int     $auto_turn_off_after,

        public int     $active_scene,

        public ?Carbon $manual_override_until,

        public Carbon  $updated_at,
    )
    {
    }
}
