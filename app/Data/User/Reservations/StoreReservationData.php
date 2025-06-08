<?php

namespace App\Data\User\Reservations;

use App\Data\User\Guests\StoreGuestData;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StoreReservationData extends Data
{
    public function __construct(
        public ?StoreGuestData $guest,

        /** @var Collection<int, StoreReservationSlotData> */
        #[Min(1)]
        public Collection      $slots
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'guest' => [Rule::requiredIf(!auth()->guard('user')->check())],
        ];
    }
}
