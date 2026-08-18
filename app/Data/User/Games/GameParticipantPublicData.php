<?php

namespace App\Data\User\Games;

use App\Models\GameParticipant;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameParticipantPublicData extends Data
{
    public function __construct(
        public string  $name,

        public ?string $level,
    )
    {
    }

    public static function fromModel(GameParticipant $participant): self
    {
        return new self(
            publicName($participant->first_name, $participant->last_name, $participant->email),
            $participant->level?->name,
        );
    }
}
