<?php

namespace App\Data\Admin\Games;

use App\Data\Admin\Courts\CourtSelectOptionData;
use App\Data\Core\CourtTypes\CourtTypeSelectOptionData;
use App\Data\Admin\GameGroups\GameGroupSelectOptionData;
use App\Enums\GameStatus;
use App\Models\Game;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameListData extends Data
{
    public function __construct(
        public int                        $id,

        public ?string                    $title,

        public ?CourtSelectOptionData     $court,

        public CourtTypeSelectOptionData  $courtType,

        public ?GameGroupSelectOptionData $gameGroup,

        public Carbon                     $start_time,

        public Carbon                     $end_time,

        public int                        $capacity,

        public int                        $taken_spots,

        public float                      $price_with_vat,

        public GameStatus                 $status,

        public Carbon                     $updated_at,
    )
    {
    }

    public static function fromModel(Game $game): self
    {
        return new self(
            $game->id,
            $game->title,
            $game->court ? CourtSelectOptionData::from($game->court) : null,
            CourtTypeSelectOptionData::from($game->courtType),
            $game->gameGroup ? GameGroupSelectOptionData::from($game->gameGroup) : null,
            $game->start_time,
            $game->end_time,
            $game->capacity,
            $game->active_participants_count ?? $game->activeParticipants()->count(),
            $game->price_with_vat,
            $game->status,
            $game->updated_at,
        );
    }
}
