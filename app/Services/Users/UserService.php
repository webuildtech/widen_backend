<?php

namespace App\Services\Users;

use App\Jobs\SubscribeUserToNewsletter;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $attributes): User
    {
        $attributes['password'] = Hash::make($attributes['password']);

        $user = User::create($attributes);
        $user->refresh();

        if($user->agreed_newsletter) {
            SubscribeUserToNewsletter::dispatch($user->id);
        }

        return $user;
    }

    public function update(User $user, array $attributes): Model
    {
        if (isset($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        $user->update($attributes);

        return $user->fresh();
    }
}
