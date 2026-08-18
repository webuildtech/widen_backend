<?php

namespace App\Data\User\Games;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameGuestData extends Data
{
    public function __construct(
        #[Email, Max(255)]
        public string          $email,

        #[Max(255)]
        public string|Optional $first_name,

        #[Max(255)]
        public string|Optional $last_name,

        #[Exists('game_levels', 'id', withoutTrashed: true)]
        public int|Optional    $game_level_id,
    )
    {
    }
}
