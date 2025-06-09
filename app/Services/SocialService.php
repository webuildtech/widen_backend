<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\One\User as SocialUserOne;
use Laravel\Socialite\Two\User as SocialUserTwo;
use Str;

class SocialService
{
    public function __construct(
        protected UserService $userService,
    ) {
    }

    public function createUser(SocialUserOne|SocialUserTwo $user): User
    {
        $userData = [
            'email' => $user->getEmail(),
            'first_name' => $user->user['given_name'] ?? $user->getName(),
            'last_name' => $user->user['family_name'],
            'password' => Str::password()
        ];

        return $this->userService->create($userData);
    }
}
