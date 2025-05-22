<?php

namespace App\Data\Admin\ReservationTimes;

use App\Models\ReservationTime;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class IndexReservationTimeData extends Data
{
    public function __construct(
        public Carbon $start_time,

        public Carbon $end_time,

        public string $court,

        public string $full_name,

        public ?string $email,

        public ?string $phone,

        public float $price_with_vat,
    )
    {
    }

    public static function fromModel(ReservationTime $reservationTime): self
    {
        if ($user = $reservationTime->reservation->user) {
            $fullName = $user->full_name;
            $email = $user->email;
            $phone = $user->phone;
        } else {
            $reservation = $reservationTime->reservation;

            $fullName = $reservation->guest_first_name . ' ' . $reservation->guest_last_name;
            $email = $reservation->guest_email;
            $phone = $reservation->guest_phone;
        }

        return new self(
            $reservationTime->start_time,
            $reservationTime->end_time,
            $reservationTime->court->name,
            $fullName,
            $email,
            $phone,
            $reservationTime->price_with_vat,
        );
    }
}
