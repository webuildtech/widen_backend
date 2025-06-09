<?php

namespace App\Data\User\Reservations;

use Illuminate\Database\Query\Builder;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationPayData extends Data
{
    public function __construct(
        #[Min(1)]
        public array $reservations_ids
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        $user = auth()->user();

        return [
            'reservations_ids.*' => [
                'required',
                new Exists(
                    'reservations',
                    'id',
                    withoutTrashed: true,
                    where: fn(Builder $builder) => $builder
                        ->where('is_paid', false)
                        ->where('owner_type', 'user')
                        ->where('owner_id', $user->id),
                )
            ],
        ];
    }
}
