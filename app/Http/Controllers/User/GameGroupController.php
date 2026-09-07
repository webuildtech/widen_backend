<?php

namespace App\Http\Controllers\User;

use App\Data\User\GameGroups\GameGroupListData;
use App\Http\Controllers\Controller;
use App\Models\GameGroup;

class GameGroupController extends Controller
{
    public function index()
    {
        $gameGroups = GameGroup::query()
            ->active()
            ->ordered()
            ->withCount(['games' => fn($query) => $query->published()->upcoming()])
            ->get();

        return GameGroupListData::collect($gameGroups);
    }

    public function show(GameGroup $gameGroup): GameGroupListData
    {
        abort_unless($gameGroup->active, 404);

        return GameGroupListData::from($gameGroup);
    }
}
