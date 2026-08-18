<?php

namespace App\Data\User\Games;

use App\Enums\GameParticipantStatus;
use App\Models\GameParticipant;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MyGameGuestData extends Data
{
    public function __construct(
        public string                $name,

        public ?string               $level,

        public GameParticipantStatus $status,

        public float                 $refunded_amount,
    )
    {
    }

    public static function fromModel(GameParticipant $participant): self
    {
        return new self(
            GameParticipantPublicData::from($participant)->name,
            $participant->level?->name,
            $participant->status,
            (float)$participant->refunded_amount,
        );
    }
}
