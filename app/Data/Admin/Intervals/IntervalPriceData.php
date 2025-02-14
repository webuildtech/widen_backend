<?php

namespace App\Data\Admin\Intervals;

use App\Enums\Day;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class IntervalPriceData extends Data
{
    public function __construct(
        public Day    $day,

        #[Regex('/^((?:[01]\d|2[0-3]):(?:00|30)|24:00)$/')]
        public string $start_time,

        #[Regex('/^((?:[01]\d|2[0-3]):(?:00|30)|24:00)$/')]
        public string $end_time,

        #[Numeric, Min(0)]
        public float  $price
    )
    {
    }

    public static function messages(): array
    {
        return [
            'start_time.required' => 'Būtina nurodyti pradžios laiką.',
            'start_time.regex' => 'Netinkamas formatas!',

            'end_time.required' => 'Būtina nurodyti pabaigos laiką.',
            'end_time.regex' => 'Netinkamas formatas!',

            'price.required' => 'Būtina nurodyti kainą.',
            'price.min' => 'Kaina turi būti bent 0.',
        ];
    }
}
