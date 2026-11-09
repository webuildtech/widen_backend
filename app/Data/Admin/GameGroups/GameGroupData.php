<?php

namespace App\Data\Admin\GameGroups;

use App\Data\Core\Media\MediaData;
use App\Models\GameGroup;
use App\Support\FrontendUrl;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameGroupData extends Data
{
    public function __construct(
        public int        $id,

        public array      $name,

        public ?array     $description,

        public int        $sort_order,

        public bool       $active,

        public bool       $hidden,

        public ?MediaData $photo,

        public string     $url,
    )
    {
    }

    public static function fromModel(GameGroup $gameGroup): self
    {
        return new self(
            $gameGroup->id,
            $gameGroup->getTranslations('name'),
            $gameGroup->getTranslations('description') ?: null,
            $gameGroup->sort_order,
            $gameGroup->active,
            $gameGroup->hidden,
            $gameGroup->photo ? MediaData::from($gameGroup->photo) : null,
            FrontendUrl::gameGroup($gameGroup->uuid),
        );
    }
}
