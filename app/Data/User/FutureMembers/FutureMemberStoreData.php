<?php

namespace App\Data\User\FutureMembers;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FutureMemberStoreData extends Data
{
    public function __construct(
        #[Email, Max(255)]
        public string          $email,

        #[Max(255)]
        public string          $first_name,

        #[Max(255)]
        public string          $last_name,

        #[Max(255)]
        public string|null     $phone,

        #[Min(1)]
        public array           $services,

        #[Min(1)]
        public array|Optional  $days,

        #[Min(1)]
        public array|Optional  $times,

        #[Min(1), In(['WIDEN Start', 'WIDEN Academy* tik vaikams nuo 10 metų', 'WIDEN Flow', 'WIDEN Prime'])]
        public string|Optional $plan,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        $daysAndTimesRequired = collect($context->payload['services'] ?? [])->contains(function ($service) {
            return in_array($service, ['Teniso aikštelės nuoma', 'Stalo teniso aikštelės nuoma']);
        });

        return [
            'days' => Rule::requiredIf($daysAndTimesRequired),
            'times' => Rule::requiredIf($daysAndTimesRequired),
            'plan' => Rule::requiredIf(fn() => collect($context->payload['services'] ?? [])->contains('Narystė')),
            'services.*' => ['required', Rule::in(['Teniso aikštelės nuoma', 'Stalo teniso aikštelės nuoma', 'Narystė'])],
            'days.*' => ['required', Rule::in(['I–V', 'VI', 'VII'])],
            'times.*' => ['required', Rule::in(['Ryte (7:00–10:00)', 'Dieną (10:00–16:00)', 'Vakare (16:00–23:00)'])],
        ];
    }
}
