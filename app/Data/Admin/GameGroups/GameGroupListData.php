<?php

namespace App\Data\Admin\GameGroups;

use App\Models\GameGroup;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameGroupListData extends Data
{
    public function __construct(
        public int     $id,

        public string  $name,

        public int     $sort_order,

        public bool    $active,

        public int     $games_count,

        public ?string $photo_url,

        public Carbon  $updated_at,
    )
    {
    }

    public static function fromModel(GameGroup $gameGroup): self
    {
        return new self(
            $gameGroup->id,
            $gameGroup->name,
            $gameGroup->sort_order,
            $gameGroup->active,
            $gameGroup->games_count ?? $gameGroup->games()->count(),
            $gameGroup->photo?->getUrl(),
            $gameGroup->updated_at,
        );
    }
}
