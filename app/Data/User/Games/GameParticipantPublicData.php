<?php

namespace App\Data\User\Games;

use App\Models\GameParticipant;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameParticipantPublicData extends Data
{
    public function __construct(
        public string  $name,

        public ?string $level,

        public int     $party,
    )
    {
    }

    public static function fromModel(GameParticipant $participant, int $party = 0): self
    {
        return new self(
            publicName($participant->first_name, $participant->last_name, $participant->email),
            $participant->level?->name,
            $party,
        );
    }

    /**
     * @param Collection<int, GameParticipant> $participants
     * @return Collection<int, self>
     */
    public static function collectForGame(Collection $participants): Collection
    {
        $partyOf = fn(GameParticipant $participant) => $participant->added_by_user_id
            ?? $participant->user_id
            ?? "participant-{$participant->id}";

        $parties = $participants->map($partyOf)->unique()->values()->flip();

        return $participants
            ->map(fn(GameParticipant $participant) => self::fromModel($participant, $parties[$partyOf($participant)] + 1))
            ->values();
    }
}
