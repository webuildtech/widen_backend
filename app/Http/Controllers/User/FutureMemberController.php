<?php

namespace App\Http\Controllers\User;

use App\Data\User\FutureMembers\FutureMemberStoreData;
use App\Http\Controllers\Controller;
use App\Models\FutureMember;
use Illuminate\Http\JsonResponse;

class FutureMemberController extends Controller
{
    public function store(FutureMemberStoreData $data): JsonResponse
    {
        FutureMember::create($data->all());

        return response()->json();
    }
}
