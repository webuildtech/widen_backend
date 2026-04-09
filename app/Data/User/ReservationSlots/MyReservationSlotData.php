<?php

namespace App\Data\User\ReservationSlots;

use App\Models\ReservationSlot;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MyReservationSlotData extends Data
{
    public function __construct(
        public int $court_id,

        public string $start_time,
    )
    {
    }

    public static function fromModel(ReservationSlot $slot): self
    {
        return new self(
            $slot->court_id,
            $slot->slot_start->format('H:i'),
        );
    }
}
