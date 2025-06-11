<?php

namespace App\Http\Controllers\User;

use App\Data\Core\CourtTypes\CourtTypeSelectOptionData;
use App\Http\Controllers\Controller;
use App\Models\CourtType;

class CourtTypeController extends Controller
{
    public function index()
    {
        $courtTypes = CourtType::all();

        return CourtTypeSelectOptionData::collect($courtTypes);
    }
}
