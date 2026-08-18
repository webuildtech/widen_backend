<?php

namespace App\Data\User\Games;

use App\Data\Core\CourtTypes\CourtTypeSelectOptionData;
use App\Models\Game;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameListData extends Data
{
    public function __construct(
        public string                    $uuid,

        public ?string                   $title,

        public CourtTypeSelectOptionData $courtType,

        public ?string                   $court_name,

        public Carbon                    $start_time,

        public Carbon                    $end_time,

        public int                       $capacity,

        public int                       $taken_spots,

        public int                       $free_spots,

        public bool                      $is_full,

        public float                     $price_with_vat,

        public ?string                   $photo_url,
    )
    {
    }

    public static function fromModel(Game $game): self
    {
        $takenSpots = $game->active_participants_count ?? $game->activeParticipants()->count();

        return new self(
            $game->uuid,
            $game->title,
            CourtTypeSelectOptionData::from($game->courtType),
            $game->court?->name,
            $game->start_time,
            $game->end_time,
            $game->capacity,
            $takenSpots,
            max(0, $game->capacity - $takenSpots),
            $takenSpots >= $game->capacity,
            $game->price_with_vat,
            $game->photo?->getUrl(),
        );
    }
}
