<?php

namespace App\Services\Games;

use App\Data\Admin\GameGroups\GameGroupStoreData;
use App\Data\Admin\GameGroups\GameGroupUpdateData;
use App\Models\GameGroup;
use App\Services\Media\MediaManager;
use Illuminate\Validation\ValidationException;

class GameGroupService
{
    public function __construct(
        protected MediaManager $mediaManager,
    )
    {
    }

    public function create(GameGroupStoreData $data): GameGroup
    {
        $gameGroup = GameGroup::create($data->except('photoFile')->all());

        $this->mediaManager->sync($gameGroup, GameGroup::PHOTO_COLLECTION, $data->photoFile);

        return $gameGroup->refresh();
    }

    public function update(GameGroup $gameGroup, GameGroupUpdateData $data): GameGroup
    {
        $gameGroup->update($data->except('photoFile', 'deletePhoto')->all());

        $this->mediaManager->sync($gameGroup, GameGroup::PHOTO_COLLECTION, $data->photoFile, $data->deletePhoto);

        return $gameGroup->refresh();
    }

    public function delete(GameGroup $gameGroup): void
    {
        $gamesCount = $gameGroup->games()->count();

        if ($gamesCount > 0) {
            throw ValidationException::withMessages([
                'game_group' => __('validation.game_groups.has_games', ['count' => $gamesCount]),
            ]);
        }

        $gameGroup->delete();
    }
}
