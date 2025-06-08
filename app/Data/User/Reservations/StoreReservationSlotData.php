<?php

namespace App\Data\User\Reservations;

use App\Models\Court;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\BeforeOrEqual;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StoreReservationSlotData extends Data
{
    public function __construct(
        #[Exists(Court::class, column: 'id')]
        public int    $court_id,

        public string $date,

        #[Regex('/^((?:[01]\d|2[0-3]):(?:00|30)|24:00)$/')]
        public string $start_time,

        #[Regex('/^((?:[01]\d|2[0-3]):(?:00|30)|24:00)$/')]
        public string $end_time,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'date' => [
                new Required(),
                new AfterOrEqual(Carbon::today()),
                new BeforeOrEqual(Carbon::today()->addDays(7))
            ]
        ];
    }
}
