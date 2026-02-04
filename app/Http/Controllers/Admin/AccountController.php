<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Auth\AccountData;
use App\Data\Admin\Auth\AccountLocaleUpdateData;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function show(): AccountData
    {
        return AccountData::from(auth()->user());
    }

    public function changeLocale(AccountLocaleUpdateData $data): AccountData
    {
        auth()->user()->update(['locale' => $data->locale]);

        return AccountData::from(auth()->user()->refresh());
    }
}
