<?php

namespace App\Data\User\Games;

use App\Data\Core\CourtTypes\CourtTypeSelectOptionData;
use App\Enums\GameParticipantStatus;
use App\Enums\GameStatus;
use App\Models\GameParticipant;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MyGameData extends Data
{
    public function __construct(
        public string                    $uuid,

        public ?string                   $title,

        public CourtTypeSelectOptionData $courtType,

        public ?string                   $court_name,

        public Carbon                    $start_time,

        public Carbon                    $end_time,

        public GameStatus                $game_status,

        public GameParticipantStatus     $status,

        public float                     $price_with_vat,

        public float                     $refunded_amount,

        public ?string                   $level,

        public bool                      $is_past,

        public Collection                $guests,

        public float                     $total_price_with_vat,

        public float                     $total_refunded_amount,

        public ?string                   $paid_by,
    )
    {
    }

    public static function fromModel(GameParticipant $participant): self
    {
        $game = $participant->game;

        $paidFor = $game->participants
            ->where('added_by_user_id', $participant->user_id)
            ->when(
                $participant->payment_id !== null,
                fn(Collection $guests) => $guests->where('payment_id', $participant->payment_id)
            );

        $guests = $paidFor
            ->sortBy(fn(GameParticipant $guest) => $guest->status === GameParticipantStatus::CANCELED)
            ->values();

        return new self(
            $game->uuid,
            $game->title,
            CourtTypeSelectOptionData::from($game->courtType),
            $game->court?->name,
            $game->start_time,
            $game->end_time,
            $game->status,
            $participant->status,
            $participant->price_with_vat,
            $participant->refunded_amount,
            $participant->level?->name,
            $game->end_time->isPast(),
            MyGameGuestData::collect($guests),
            (float)$participant->price_with_vat + (float)$paidFor->sum('price_with_vat'),
            (float)$participant->refunded_amount + (float)$paidFor->sum('refunded_amount'),
            $participant->addedBy
                ? publicName($participant->addedBy->first_name, $participant->addedBy->last_name, $participant->addedBy->email)
                : null,
        );
    }
}
