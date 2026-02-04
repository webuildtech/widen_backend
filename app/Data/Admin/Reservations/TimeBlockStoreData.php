<?php

namespace App\Data\Admin\Reservations;

use App\Enums\Day;
use App\Support\RegexPatterns;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TimeBlockStoreData extends Data
{
    public function __construct(
        public Day    $day,

        #[Regex(RegexPatterns::TIME_HALF_HOUR)]
        public string $start_time,

        #[Regex(RegexPatterns::TIME_HALF_HOUR)]
        public string $end_time,
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
        ];
    }
}
