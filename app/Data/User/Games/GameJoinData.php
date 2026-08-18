<?php

namespace App\Data\User\Games;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameJoinData extends Data
{
    public function __construct(
        #[Exists('game_levels', 'id', withoutTrashed: true)]
        public int|Optional     $game_level_id,

        #[Max(255)]
        public string|Optional  $discount_code,

        /** @var Collection<int, GameGuestData> */
        public Collection|Optional $guests,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'guests' => ['sometimes', 'array', 'max:' . config('games.max_guests_per_join')],
        ];
    }
}
