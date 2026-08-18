<?php

namespace App\Data\Admin\Games;

use App\Models\Game;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameResultData extends Data
{
    public function __construct(
        /** @var Collection<int, Game> */
        public Collection $games,

        /** @var Collection<int, GameCourtConflictData> */
        public Collection $conflicts,
    )
    {
    }

    public static function success(Collection $games): self
    {
        return new self($games, collect());
    }

    public static function blocked(Collection $conflicts): self
    {
        return new self(collect(), $conflicts);
    }

    public function hasConflicts(): bool
    {
        return $this->conflicts->isNotEmpty();
    }
}
