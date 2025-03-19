<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Court;
use App\Models\Group;
use App\Models\Interval;
use App\Models\IntervalPrice;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use App\Models\ReservationTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
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

        Relation::enforceMorphMap([
            'admin' => Admin::class,
            'court' => Court::class,
            'group' => Group::class,
            'interval' => Interval::class,
            'intervalPrice' => IntervalPrice::class,
            'payment' => Payment::class,
            'plan' => Plan::class,
            'reservation' => Reservation::class,
            'reservationSlot' => ReservationSlot::class,
            'reservationTime' => ReservationTime::class,
            'user' => User::class,
        ]);

        Carbon::macro('parseWithAppTimezone', fn ($time) => Carbon::parse($time)->setTimezone(config('app.timezone')));

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return env('APP_FRONTEND_URL') . '/reset-password?token='.$token;
        });
    }
}
