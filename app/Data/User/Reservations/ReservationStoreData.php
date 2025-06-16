<?php

namespace App\Data\User\Reservations;

use App\Data\User\Guests\GuestStoreData;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationStoreData extends Data
{
    public function __construct(
        public ?GuestStoreData      $guest,

        public string|Optional|null $discount_code,

        /** @var Collection<int, ReservationSlotStoreData> */
        #[Min(1)]
        public Collection           $slots
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'guest' => [Rule::requiredIf(!auth()->guard('user')->check())],
        ];
    }
}
