<?php

namespace App\Data\Admin\Downtimes;

use Carbon\Carbon;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DowntimeInputData extends Data
{
    public function __construct(
        #[Exists('courts', 'id', withoutTrashed: true)]
        public int                  $court_id,

        #[Date]
        public Carbon               $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon               $date_to,

        #[Regex('/^((?:[01]\d|2[0-3]):(?:00|30)|24:00)$/')]
        public string               $start_time,

        #[Regex('/^((?:[01]\d|2[0-3]):(?:00|30)|24:00)$/')]
        public string               $end_time,

        public string|null|Optional $comment
    )
    {
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $startTime = $validator->getValue('start_time');
            $endTime = $validator->getValue('end_time');

            if ($startTime && $endTime && $startTime >= $endTime) {
                $validator->errors()->add("end_time", 'Pabaigos laikas turi būti didesnis nei pradžios laikas');
            }
        });
    }
}
