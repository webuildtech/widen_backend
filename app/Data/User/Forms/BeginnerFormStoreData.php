<?php

namespace App\Data\User\Forms;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class BeginnerFormStoreData extends Data
{
    public function __construct(
        #[Email, Max(255)]
        public string $email,

        #[Max(255)]
        public string $first_name,

        #[Max(255)]
        public string $last_name,

        #[Max(255)]
        public string $phone,

        #[Min(0)]
        public int    $age,

        public string $groups,

        public bool   $marketing_consent,

        #[Accepted]
        public bool   $service_consent,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'groups' => Rule::in([
                'Savaitgaliais: 11:00 - 12:00 (30 EUR)',
                '(TIK MOTERIMS) Savaitgaliais: 11:00 - 12:00 (30 EUR)',
                'Savaitgaliais: 12:00 - 13:00 (30 EUR)',
                '(TIK MOTERIMS) Savaitgaliais: 12:00 - 13:00 (30 EUR)'
            ]),
        ];
    }
}
