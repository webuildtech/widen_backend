<?php

namespace App\Data\Admin\LitecomZones;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LitecomZoneOnData extends Data
{
    public function __construct(
        #[In([1, 2, 3, 4])]
        public int     $scene,

        #[After('now'), Date]
        public ?Carbon $manual_override_until,
    )
    {
    }
}
