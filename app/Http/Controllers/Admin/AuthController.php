<?php

namespace App\Http\Controllers\Admin;

use App\Data\Auth\AuthData;
use App\Data\Auth\LoginData;
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
                "errors" => ['email' => ['Jūsų prisijungimo duomenys nesutampa su sistemos duomenimis.']]
            ], 406);
        }

        if ($admin->blocked) {
            return response()->json([
                "errors" => ['email' => ['Jūsų paskyrą užblokavo darbdavys.']]
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
