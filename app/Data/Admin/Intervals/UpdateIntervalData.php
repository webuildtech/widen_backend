<?php

namespace App\Data\Admin\Intervals;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UpdateIntervalData extends Data
{
    public function __construct(
        #[Rule(['max:255'])]
        public string|Optional      $name,

        #[Rule(['max:255'])]
        public string|Optional|null $inside_name,

        #[Date]
        public Carbon|Optional      $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon|Optional      $date_to,

        /** @var Collection<int, IntervalPriceData> */
        public Collection|Optional  $prices
    )
    {
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $prices = $validator->getValue('prices');
            $interval = request()->route('interval');
            $dateFrom = $validator->getValue('date_from');
            $dateTo = $validator->getValue('date_to');

            if (!$dateFrom || !$dateTo) {
                if ($dateFrom) {
                    Carbon::parseWithAppTimezone($dateFrom)->greaterThan($interval['date_to']) && $validator->errors()->add(
                        "date_from",
                        'Galioja nuo turi būti mažesnis nei galioja iki'
                    );
                }

                if ($dateTo) {
                    Carbon::parseWithAppTimezone($dateTo)->lessThan($interval['date_from']) && $validator->errors()->add(
                        "date_to",
                        'Galioja iki turi būti didesnis nei galioja nuo'
                    );
                }
            }

            if (!$prices || !is_array($prices)) {
                return;
            }

            $byDay = [];
            foreach ($prices as $index => $price) {
                if (!isset($price['start_time']) || !isset($price['end_time'])) {
                    continue;
                }

                if ($price['start_time'] >= $price['end_time']) {
                    $validator->errors()->add(
                        "prices.{$index}.end_time",
                        'Pabaigos laikas turi būti didesnis nei pradžios laikas'
                    );
                }

                $day = $price['day'];

                if (!isset($byDay[$day])) {
                    $byDay[$day] = [];
                }

                $byDay[$day][] = [
                    'index' => $index,
                    'start_time' => $price['start_time'],
                    'end_time' => $price['end_time'],
                ];
            }


            foreach ($byDay as $slots) {
                for ($i = 1; $i < count($slots); $i++) {
                    $prev = $slots[$i - 1];
                    $current = $slots[$i];

                    if ($current['start_time'] < $prev['end_time']) {
                        $validator->errors()->add(
                            "prices.{$current['index']}.start_time",
                            'Naujas intervalas turi prasidėti ne anksčiau, nei baigėsi ankstesnis'
                        );
                    }
                }
            }
        });
    }
}
