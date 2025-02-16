<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Users\SelectUserData;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function all()
    {
        return SelectUserData::collect(User::select(['id', 'first_name', 'last_name'])->get());
    }
}
