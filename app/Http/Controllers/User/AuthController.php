<?php

namespace App\Http\Controllers\User;

use App\Data\User\Auth\AuthData;
use App\Data\User\Auth\LoginData;
use App\Data\User\Auth\PasswordRecoveryData;
use App\Data\User\Auth\PasswordResetData;
use App\Data\User\Auth\RegisterData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Models\UserRepository;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {
    }

    public function login(LoginData $data): JsonResponse|AuthData
    {
        $user = User::where('email', $data->email)->first();

        if (!$user || !\Hash::check($data->password, $user->password)) {
            return response()->json([
                "errors" => ['email' => ['Jūsų prisijungimo duomenys nesutampa su sistemos duomenimis.']]
            ], 406);
        }

        return AuthData::from([
            "authUser" => $user,
            'accessToken' => $user->createToken(
                'access_token', ['*'], Carbon::now()->addDays(request()->get('remember') ? 31 : 1)
            )->plainTextToken
        ]);
    }

    public function register(RegisterData $data)
    {
        $user = $this->userRepository->create($data);

        return AuthData::from([
            "authUser" => $user,
            'accessToken' => $user->createToken('access_token', ['*'], Carbon::now()->addDay())->plainTextToken
        ]);
    }

    public function passwordRecovery(PasswordRecoveryData $data): JsonResponse
    {
        $status = Password::broker('users')->sendResetLink(['email' => $data->email]);

        return response()->json(['message' => __($status)], $status === Password::RESET_LINK_SENT ? 200 : 406);
    }

    public function passwordReset(PasswordResetData $data): JsonResponse
    {
        $status = Password::broker('users')->reset(
            $data->toArray(),
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->save();

                $user->tokens()->delete();
            }
        );

        return response()->json(['message' => __($status)], $status === Password::PASSWORD_RESET ? 200 : 406);
    }

    public function logout(): array
    {
        request()->user()->currentAccessToken()->delete();

        return [];
    }
}
