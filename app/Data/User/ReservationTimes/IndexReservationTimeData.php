<?php

namespace App\Data\User\ReservationTimes;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class IndexReservationTimeData extends Data
{
    public function __construct(
        #[In(['active', 'past', 'cancelled'])]
        public string $type,
    )
    {
    }
}
