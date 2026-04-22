<?php

namespace App\Data\Admin\CourtSlot;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtFilterData extends Data
{
    public function __construct(
        public ?int $courtTypeId,

        #[AfterOrEqual('today')]
        public Carbon $date,
    )
    {
    }
}
