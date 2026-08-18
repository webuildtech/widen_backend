<?php

namespace App\Data\User\Games;

use App\Data\Core\CourtTypes\CourtTypeSelectOptionData;
use App\Data\Core\Games\GameLevelSelectOptionData;
use App\Enums\GameStatus;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameDetailData extends Data
{
    public function __construct(
        public string                    $uuid,

        public ?string                   $title,

        public ?string                   $description,

        public CourtTypeSelectOptionData $courtType,

        public ?string                   $court_name,

        public Carbon                    $start_time,

        public Carbon                    $end_time,

        public int                       $capacity,

        public int                       $taken_spots,

        public int                       $free_spots,

        public bool                      $is_full,

        public bool                      $is_joinable,

        public GameStatus                $status,

        public float                     $price_with_vat,

        public ?string                   $photo_url,

        /** @var Collection<int, GameParticipantPublicData> */
        public Collection                $participants,

        public Collection                $levels,

        public int                       $max_guests,
    )
    {
    }

    public static function fromModel(Game $game): self
    {
        $game->loadMissing(['courtType.gameLevels', 'court']);

        $participants = $game->activeParticipants()->with('level')->get();
        $takenSpots = $participants->count();
        $freeSpots = max(0, $game->capacity - $takenSpots);

        return new self(
            $game->uuid,
            $game->title,
            $game->description,
            CourtTypeSelectOptionData::from($game->courtType),
            $game->court?->name,
            $game->start_time,
            $game->end_time,
            $game->capacity,
            $takenSpots,
            $freeSpots,
            $freeSpots === 0,
            $game->status === GameStatus::PUBLISHED && $game->start_time->isFuture() && $freeSpots > 0,
            $game->status,
            $game->price_with_vat,
            $game->photo?->getUrl(),
            GameParticipantPublicData::collect($participants),
            GameLevelSelectOptionData::collect($game->courtType->gameLevels->where('active', true)->values()),
            config('games.max_guests_per_join'),
        );
    }
}
