<?php

namespace App\Data\Admin\Games;

use App\Enums\GameParticipantStatus;
use App\Models\GameParticipant;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameParticipantListData extends Data
{
    public function __construct(
        public int                    $id,

        public ?int                   $user_id,

        public string                 $full_name,

        public string                 $email,

        public ?string                $level,

        public GameParticipantStatus  $status,

        public float                  $price_with_vat,

        public float                  $discount,

        public float                  $refunded_amount,

        public ?string                $added_by,

        public ?Carbon                $joined_at,
    )
    {
    }

    public static function fromModel(GameParticipant $participant): self
    {
        return new self(
            $participant->id,
            $participant->user_id,
            $participant->full_name,
            $participant->email,
            $participant->level?->name,
            $participant->status,
            $participant->price_with_vat,
            $participant->discount,
            $participant->refunded_amount,
            $participant->addedBy?->full_name,
            $participant->joined_at,
        );
    }
}
