<?php

namespace App\Data\User\ReservationTimes;

use App\Models\ReservationSlot;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationSlotData extends Data
{
    public function __construct(
        public string $slot_start,

        public string $slot_end,

        public float  $price,

        public bool   $is_refunded,

        public bool   $is_free_from_plan,
    )
    {
    }

    public static function fromModel(ReservationSlot $slot): self
    {
        return new self(
            $slot->slot_start->format('H:i'),
            $slot->slot_end->format('H:i'),
            $slot->price,
            $slot->is_refunded,
            $slot->is_free_from_plan,
        );
    }

}
