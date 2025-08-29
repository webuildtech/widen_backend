<?php

namespace App\Data\Admin\Reservations;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationBulkActionData extends Data
{
    public function __construct(
        #[Min(1)]
        /** @var array<int> */
        public array $ids,

        #[In(['cancel', 'delete'])]
        public string $action,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'ids.*' => ['required', new Exists('reservations', 'id', withoutTrashed: true)],
        ];
    }
}
