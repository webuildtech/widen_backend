<?php

namespace App\Http\Controllers;

use App\Data\AccountData;

class AccountController extends Controller
{
    public function show(): AccountData
    {
        return AccountData::from(auth()->user());
    }
}
