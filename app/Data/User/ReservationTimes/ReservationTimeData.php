<?php

namespace App\Data\User\ReservationTimes;

use App\Models\ReservationTime;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Illuminate\Support\Collection;

#[TypeScript]
class ReservationTimeData extends Data
{
    public function __construct(
        public int        $id,

        public string     $courtName,

        public string     $date,

        public string     $start_time,

        public string     $end_time,

        public float      $price_with_vat,

        public float      $refunded_amount,

        public int        $used_free_slots,

        public int        $refunded_free_slots,

        public float      $is_past,

        public ?Carbon    $cancelled_at,

        /** @var Collection<int, ReservationSlotData> */
        public Collection $slots,
    )
    {
    }

    public static function fromModel(ReservationTime $time): self
    {
        return new self(
            $time->id,
            $time->court->name,
            $time->start_time->format('Y-m-d'),
            $time->start_time->format('H:i'),
            $time->end_time->format('H:i'),
            $time->price_with_vat,
            $time->refunded_amount,
            $time->used_free_slots,
            $time->refunded_free_slots,
            now()->isAfter($time->end_time),
            $time->canceled_at,
            $time->canceled_at && $time->slots()->where('try_sell', true)->exists()
                ? ReservationSlotData::collect($time->slots) : collect(),
        );
    }
}
