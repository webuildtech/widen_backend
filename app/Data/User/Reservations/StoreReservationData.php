<?php

namespace App\Data\User\Reservations;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StoreReservationData extends Data
{
    public function __construct(
        public ?string    $guest_email,

        public ?string    $guest_first_name,

        public ?string    $guest_last_name,

        public ?string    $guest_phone,

        public ?int       $user_id,

        public int        $usedFreeSlots,

        /** @var Collection<int, ReservationSlotData> */
        #[Min(1)]
        public Collection $slots
    )
    {
        if ($user = auth()->guard('user')->user()) {
            $this->guest_email = null;
            $this->guest_first_name = null;
            $this->guest_last_name = null;
            $this->guest_phone = null;
            $this->user_id = $user->id;
        } else {
            $this->user_id = null;
        }
    }

    public static function rules(ValidationContext $context): array
    {
        $required = !auth()->guard('user')->check();

        return [
            'guest_email' => [Rule::requiredIf($required), new Email(), new Max(255)],
            'guest_first_name' => [Rule::requiredIf($required), new Max(255)],
            'guest_last_name' => [Rule::requiredIf($required), new Max(255)],
            'guest_phone' => [Rule::requiredIf($required), new Max(255)],
        ];
    }
}
