<?php

namespace App\Http\Controllers\User;

use App\Data\Core\Games\GameLevelSelectOptionData;
use App\Http\Controllers\Controller;
use App\Models\CourtType;

class GameLevelController extends Controller
{
    public function __invoke(CourtType $courtType)
    {
        return GameLevelSelectOptionData::collect(
            $courtType->gameLevels()->where('active', true)->get()
        );
    }
}
