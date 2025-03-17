<?php

namespace App\Http\Controllers\User;

use App\Data\User\Plans\PlanData;
use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::whereActive(true)->orderBy('price')->get();

        return PlanData::collect($plans);
    }
}
