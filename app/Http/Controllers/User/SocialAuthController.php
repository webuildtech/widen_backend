<?php

namespace App\Http\Controllers\User;

use App\Data\User\Auth\AuthData;
use App\Data\User\Auth\SocialData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SocialService;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function __construct(
        protected SocialService $socialService,
    ) {
    }

    public function __invoke(SocialData $data)
    {
        try {
            $userData = Socialite::driver($data->social->value)->stateless()->userFromToken($data->accessToken);

            $user = User::whereEmail($userData->getEmail())->first() ?? $this->socialService->createUser($userData);

            return AuthData::from([
                "authUser" => $user,
                'accessToken' => $user->createToken('access_token', ['*'], Carbon::now()->addDays(90))->plainTextToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Nepavyko prisijungti, bandykite dar kartą.'], 406);
        }
    }
}
