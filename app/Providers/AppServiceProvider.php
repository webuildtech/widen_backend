<?php

namespace App\Providers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Carbon::macro('parseWithAppTimezone', fn ($time) => Carbon::parse($time)->setTimezone(config('app.timezone')));

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return env('APP_FRONTEND_URL') . '/reset-password?token='.$token;
        });
    }
}
