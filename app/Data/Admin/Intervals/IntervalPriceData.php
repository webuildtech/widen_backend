<?php

namespace App\Data\Admin\Intervals;

use App\Enums\Day;
use App\Support\RegexPatterns;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class IntervalPriceData extends Data
{
    public function __construct(
        public Day                 $day,

        #[Regex(RegexPatterns::TIME_HALF_HOUR)]
        public string              $start_time,

        #[Regex(RegexPatterns::TIME_HALF_HOUR)]
        public string              $end_time,

        #[Numeric, Min(0)]
        public float               $price,

        /** @var Collection<int, IntervalPriceGroupData> */
        #[LoadRelation]
        public Collection|Optional $groups
    )
    {
    }

    public static function messages(): array
    {
        return [
            'start_time.required' => __('validation.availability.start_time.required'),
            'start_time.regex' => __('validation.availability.start_time.format'),

            'end_time.required' => __('validation.availability.end_time.required'),
            'end_time.regex' => __('validation.availability.end_time.format'),

            'price.required' => __('validation.availability.price.required'),
            'price.min' => __('validation.availability.price.min'),
        ];
    }
}
