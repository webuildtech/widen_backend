<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Auth\AccountData;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function show(): AccountData
    {
        return AccountData::from(auth()->user());
    }
}
