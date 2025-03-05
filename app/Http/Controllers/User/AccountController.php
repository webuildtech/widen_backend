<?php

namespace App\Http\Controllers\User;

use App\Data\User\AccountData;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function show(): AccountData
    {
        return AccountData::from(auth()->user());
    }
}
