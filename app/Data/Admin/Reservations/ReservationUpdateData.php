<?php

namespace App\Data\Admin\Reservations;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationUpdateData extends Data
{
    public function __construct(
        /** @var Collection<int, ReservationSlotStoreData> */
        #[Min(1)]
        public Collection           $slots
    )
    {
    }
}
