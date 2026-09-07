<?php

namespace App\Data\User\GameGroups;

use App\Models\GameGroup;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameGroupListData extends Data
{
    public function __construct(
        public string  $uuid,

        public string  $name,

        public ?string $description,

        public ?string $photo_url,

        public int     $games_count,
    )
    {
    }

    public static function fromModel(GameGroup $gameGroup): self
    {
        return new self(
            $gameGroup->uuid,
            $gameGroup->name,
            $gameGroup->description,
            $gameGroup->photo?->getUrl(),
            $gameGroup->games_count ?? $gameGroup->games()->published()->upcoming()->count(),
        );
    }
}
