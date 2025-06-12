<?php

namespace App\Data\User\Reservations;

use App\Data\Core\CourtTypes\CourtTypeSelectOptionData;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationData extends Data
{
    public function __construct(
        public int                       $id,

        public string                    $courtName,

        public CourtTypeSelectOptionData $courtType,

        public string                    $date,

        public string                    $start_time,

        public string                    $end_time,

        public float                     $price_with_vat,

        public float                     $refunded_amount,

        public bool                      $is_paid,

        public float                     $is_past,

        public ?Carbon                   $cancelled_at,

        /** @var Collection<int, ReservationSlotData> */
        public Collection                $slots,
    )
    {
    }

    public static function fromModel(Reservation $reservation): self
    {
        return new self(
            $reservation->id,
            $reservation->court->name,
            CourtTypeSelectOptionData::from($reservation->court->courtType),
            $reservation->start_time->format('Y-m-d'),
            $reservation->start_time->format('H:i'),
            $reservation->end_time->format('H:i'),
            $reservation->price_with_vat,
            $reservation->refunded_amount,
            $reservation->is_paid,
            now()->isAfter($reservation->end_time),
            $reservation->canceled_at,
            $reservation->canceled_at && $reservation->slots()->where('try_sell', true)->exists()
                ? ReservationSlotData::collect($reservation->slots) : collect(),
        );
    }
}
