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

        /**
         * Court types the code is checked for, for purchases that have no court to derive them from.
         * Takes precedence over `court_ids`.
         *
         * @var array<int>|null
         */
        public ?array  $court_type_ids,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'court_ids.*' => ['required', new Exists('courts', 'id', withoutTrashed: true)],
            'court_type_ids.*' => ['required', new Exists('court_types', 'id', withoutTrashed: true)],
        ];
    }
}
