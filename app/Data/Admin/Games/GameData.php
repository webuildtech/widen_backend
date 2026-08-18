<?php

namespace App\Data\Admin\Games;

use App\Data\Core\Media\MediaData;
use App\Enums\GameStatus;
use App\Models\Game;
use App\Support\FrontendUrl;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameData extends Data
{
    public function __construct(
        public int         $id,

        public string      $uuid,

        public int         $court_type_id,

        public ?int        $court_id,

        /** All translations, so the admin can edit both languages regardless of the one being browsed in. */
        public ?array      $title,

        public ?array      $description,

        public string      $date,

        public string      $start_time,

        public string      $end_time,

        public int         $capacity,

        public int         $taken_spots,

        public float       $price_with_vat,

        public GameStatus  $status,

        public ?Carbon     $canceled_at,

        public ?MediaData  $photo,

        public string      $url,

        /** @var Collection<int, GameParticipantListData> */
        public Collection  $participants,
    )
    {
    }

    public static function fromModel(Game $game): self
    {
        $game->loadMissing(['participants.level', 'participants.addedBy']);

        return new self(
            $game->id,
            $game->uuid,
            $game->court_type_id,
            $game->court_id,
            $game->getTranslations('title') ?: null,
            $game->getTranslations('description') ?: null,
            $game->start_time->format('Y-m-d'),
            $game->start_time->format('H:i'),
            $game->end_time->format('H:i'),
            $game->capacity,
            $game->activeParticipants()->count(),
            $game->price_with_vat,
            $game->status,
            $game->canceled_at,
            $game->photo ? MediaData::from($game->photo) : null,
            FrontendUrl::game($game->uuid),
            GameParticipantListData::collect($game->participants),
        );
    }
}
