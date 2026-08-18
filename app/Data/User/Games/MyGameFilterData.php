<?php

namespace App\Data\User\Games;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MyGameFilterData extends Data
{
    public function __construct(
        public string $type = 'upcoming',
    )
    {
    }

    public static function rules(): array
    {
        return [
            'type' => ['sometimes', Rule::in(['upcoming', 'past', 'canceled'])],
        ];
    }
}
