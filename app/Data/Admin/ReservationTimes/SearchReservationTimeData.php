<?php

namespace App\Data\Admin\ReservationTimes;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SearchReservationTimeData extends Data
{
    public function __construct(
        /** @var array<int> */
        public array|Optional $courts_ids,

        #[Date]
        public Carbon         $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon         $date_to,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'courts_ids.*' => ['required', new Exists('courts', 'id', withoutTrashed: true)],
        ];
    }
}
