<?php

namespace App\Data\Admin\Intervals;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Illuminate\Validation\Validator;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Illuminate\Support\Collection;

#[TypeScript]
class IntervalStoreData extends Data
{
    public function __construct(
        #[Rule(['max:255'])]
        public string              $name,

        #[Rule(['max:255'])]
        public ?string             $inside_name,

        #[Date]
        public Carbon              $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon              $date_to,

        /** @var Collection<int, IntervalPriceData> */
        public Collection|Optional $prices
    )
    {
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $prices = $validator->getValue('prices');

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
                        __('validation.availability.slot.end_after_start')
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
                            __('validation.availability.slot.no_overlap')
                        );
                    }
                }
            }
        });
    }
}
