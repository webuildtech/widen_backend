<?php

namespace App\Http\Controllers\User;

use App\Data\User\Account\ChangeAccountPasswordData;
use App\Data\User\Account\UpdateAccountData;
use App\Data\User\AccountData;
use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\Models\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    public function show(): AccountData
    {
        return AccountData::from(auth()->user());
    }

    public function update(UpdateAccountData $data): AccountData
    {
        \Log::info('aaa', $data->toArray());
        $user = $this->userRepository->update(auth()->user(), $data);

        return AccountData::from($user);
    }

    public function changePassword(ChangeAccountPasswordData $data): JsonResponse
    {
        $user = auth()->user();

        $user->update(['password' => Hash::make($data->password)]);

        return response()->json();
    }
}
