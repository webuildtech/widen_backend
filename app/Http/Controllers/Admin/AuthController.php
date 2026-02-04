<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Auth\AuthData;
use App\Data\Admin\Auth\LoginData;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginData $loginData): JsonResponse|AuthData
    {
        $admin = Admin::where('email', $loginData->email)->first();

        if (!$admin || !\Hash::check($loginData->password, $admin->password)) {
            return response()->json([
                "errors" => ['email' => [__('auth.login.invalid_credentials')]]
            ], 406);
        }

        if ($admin->blocked) {
            return response()->json([
                "errors" => ['email' => [__('auth.login.account_blocked_by_employer')]]
            ], 406);
        }

        return AuthData::from([
            "authUser" => $admin,
            'accessToken' => $admin->createToken(
                'access_token',
                ['*'],
                Carbon::now()->addDays(request()->get('remember') ? 31 : 1)
            )->plainTextToken
        ]);
    }

    public function logout(): array
    {
        request()->user()->currentAccessToken()->delete();

        return [];
    }
}
