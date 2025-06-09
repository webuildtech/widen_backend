<?php

namespace App\Data\User\Reservations;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationFilterData extends Data
{
    public function __construct(
        #[In(['unpaid', 'active', 'past', 'cancelled'])]
        public string  $type,

        public ?Carbon $date,
    )
    {
    }
}
