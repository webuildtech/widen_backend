<?php

namespace App\Data\Admin\Reservations;

use App\Data\Admin\Courts\CourtSelectOptionData;
use App\Data\Core\Owners\OwnerData;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationListData extends Data
{
    public function __construct(
        public int                   $id,

        public Carbon                $start_time,

        public Carbon                $end_time,

        public CourtSelectOptionData $court,

        public string                $owner_type,

        public OwnerData             $owner,

        public float                 $price_with_vat,

        public float                 $refunded_amount,

        public bool                  $is_paid,

        public ?Carbon               $paid_at,

        public ?Carbon               $canceled_at,

        public Carbon                $updated_at,
    )
    {
    }
}
