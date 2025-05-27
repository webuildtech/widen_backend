<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Models\UserRepository;
use Laravel\Socialite\One\User as SocialUser;

class SocialService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {
    }

    public function createUser(SocialUser $user): User
    {
        $userData = [
            'email' => $user->getEmail(),
            'first_name' => $user->user['given_name'] ?? $user->getName(),
            'last_name' => $user->user['family_name'],
        ];

        return $this->userRepository->createFromSocial($userData);
    }
}
