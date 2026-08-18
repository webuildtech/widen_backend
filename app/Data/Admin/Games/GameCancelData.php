<?php

namespace App\Data\Admin\Games;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameCancelData extends Data
{
    public function __construct(
        public ?string $reason = null,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
