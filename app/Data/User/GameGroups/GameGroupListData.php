<?php

namespace App\Data\User\GameGroups;

use App\Models\Game;
use App\Models\GameGroup;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameGroupListData extends Data
{
    public function __construct(
        public string  $uuid,

        public string  $name,

        public ?string $description,

        public ?string $photo_url,

        public int     $games_count,

        public int     $free_spots,
    )
    {
    }

    public static function fromModel(GameGroup $gameGroup): self
    {
        $games = $gameGroup->relationLoaded('games')
            ? $gameGroup->games
            : $gameGroup->games()->published()->upcoming()->withCount('activeParticipants')->get();

        return new self(
            $gameGroup->uuid,
            $gameGroup->name,
            $gameGroup->description,
            $gameGroup->photo?->getUrl(),
            $games->count(),
            $games->sum(fn(Game $game) => max(0, $game->capacity - $game->active_participants_count)),
        );
    }
}
