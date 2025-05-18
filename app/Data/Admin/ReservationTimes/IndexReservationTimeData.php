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

        public string $user,
    )
    {
    }

    public static function fromModel(ReservationTime $reservationTime): self
    {
        return new self(
            $reservationTime->start_time,
            $reservationTime->end_time,
            $reservationTime->court->name,
            $reservationTime->reservation->user ? $reservationTime->reservation->user->full_name :
                $reservationTime->reservation->guest_first_name . ' ' . $reservationTime->reservation->guest_last_name,
        );
    }
}
