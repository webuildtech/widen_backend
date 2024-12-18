<?php

namespace App\Http\Controllers;

use App\Data\Auth\AuthData;
use App\Data\Auth\LoginData;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginData $loginData): JsonResponse|AuthData
    {
        $validatedData = $loginData->toArray();
        unset($validatedData['remember']);

        if (!auth()->attempt($validatedData)) {
            return response()->json([
                "errors" => ['email' => ['Your login data does not match the system data.']]
            ], 406);
        }

        $user = auth()->user();

        return AuthData::from([
            "authUser" => $user,
            'accessToken' => $user->createToken(
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
