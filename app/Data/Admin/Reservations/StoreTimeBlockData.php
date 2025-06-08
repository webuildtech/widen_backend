<?php

namespace App\Data\Admin\Reservations;

use App\Enums\Day;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StoreTimeBlockData extends Data
{
    public function __construct(
        public Day    $day,

        #[Regex('/^((?:[01]\d|2[0-3]):(?:00|30)|24:00)$/')]
        public string $start_time,

        #[Regex('/^((?:[01]\d|2[0-3]):(?:00|30)|24:00)$/')]
        public string $end_time,
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
        ];
    }
}
