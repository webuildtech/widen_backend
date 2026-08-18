<?php

namespace App\Data\Admin\Reservations;

use App\Data\Admin\Courts\CourtSelectOptionData;
use App\Data\Core\Owners\OwnerData;
use App\Models\Reservation;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationCalendarData extends Data
{
    public function __construct(
        public int                   $id,

        public Carbon                $start_time,

        public Carbon                $end_time,

        public CourtSelectOptionData $court,

        public string                $owner_type,

        public OwnerData             $owner,

        public float                 $price_with_vat,

        public bool                  $is_paid,

        public ?Carbon               $canceled_at,

        public ?string               $comment,

        public ?string               $game_uuid = null,
    )
    {
    }

    public static function fromModel(Reservation $reservation): self
    {
        return new self(
            $reservation->id,
            $reservation->start_time,
            $reservation->end_time,
            CourtSelectOptionData::from($reservation->court),
            $reservation->owner_type,
            OwnerData::from($reservation->owner),
            $reservation->price_with_vat,
            $reservation->is_paid,
            $reservation->canceled_at,
            $reservation->comment,
            $reservation->owner_type === 'game' ? $reservation->owner?->uuid : null,
        );
    }
}
