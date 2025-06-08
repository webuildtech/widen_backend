<?php

namespace App\Data\Admin\Reservations;

use App\Enums\CourtType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StoreMultiReservationData extends Data
{
    public function __construct(
        #[Exists('users', 'id', withoutTrashed: true)]
        public int               $user_id,

        public CourtType         $court_type,

        #[Exists('courts', 'id', withoutTrashed: true)]
        public int|null|Optional $court_id,

        #[Date]
        public Carbon            $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon            $date_to,

        public bool              $force_create,

        /** @var Collection<int, StoreTimeBlockData> */
        #[Min(1)]
        public Collection        $time_blocks
    )
    {
    }
}
