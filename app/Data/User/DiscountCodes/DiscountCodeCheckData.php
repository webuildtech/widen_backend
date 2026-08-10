<?php

namespace App\Data\User\DiscountCodes;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiscountCodeCheckData extends Data
{
    public function __construct(
        #[Max(255)]
        public string  $code,

        /**
         * Courts the code is checked for. An empty array means the code is not used for a reservation,
         * null means the courts are unknown.
         *
         * @var array<int>|null
         */
        public ?array  $court_ids,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'court_ids.*' => ['required', new Exists('courts', 'id', withoutTrashed: true)],
        ];
    }
}
