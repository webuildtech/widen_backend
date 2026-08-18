<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Advertisement;
use App\Models\Court;
use App\Models\CourtType;
use App\Models\DiscountCode;
use App\Models\Downtime;
use App\Models\Forms\BeginnerForm;
use App\Models\FutureMember;
use App\Models\Game;
use App\Models\GameLevel;
use App\Models\GameParticipant;
use App\Models\Group;
use App\Models\Guest;
use App\Models\Interval;
use App\Models\IntervalPrice;
use App\Models\Invoice;
use App\Models\LitecomZone;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\PlanCourtTypeRule;
use App\Models\PlanCourtTypeRuleSlot;
use App\Models\PlanFeature;
use App\Models\PlanPrice;
use App\Models\Reservation;
use App\Models\ReservationGroup;
use App\Models\ReservationSlot;
use App\Models\User;
use App\Services\Litecom\LitecomManager;
use App\Support\FrontendUrl;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Spatie\Translatable\Facades\Translatable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LitecomManager::class, function () {
            return new LitecomManager(config('litecom'));
        });
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Translatable::fallback(
            fallbackLocale: FrontendUrl::DEFAULT_LOCALE->value,
            fallbackAny: true,
        );

        Relation::enforceMorphMap([
            'advertisement' => Advertisement::class,
            'futureMember' => FutureMember::class,
            'admin' => Admin::class,
            'courtType' => CourtType::class,
            'court' => Court::class,
            'game' => Game::class,
            'gameParticipant' => GameParticipant::class,
            'gameLevel' => GameLevel::class,
            'group' => Group::class,
            'interval' => Interval::class,
            'intervalPrice' => IntervalPrice::class,
            'payment' => Payment::class,
            'plan' => Plan::class,
            'planPrice' => PlanPrice::class,
            'planFeature' => PlanFeature::class,
            'planCourtTypeRule' => PlanCourtTypeRule::class,
            'planCourtTypeRuleSlot' => PlanCourtTypeRuleSlot::class,
            'reservation' => Reservation::class,
            'reservationSlot' => ReservationSlot::class,
            'reservationGroup' => ReservationGroup::class,
            'user' => User::class,
            'guest' => Guest::class,
            'downtime' => Downtime::class,
            'discountCode' => DiscountCode::class,
            'invoice' => Invoice::class,
            'litecomZone' => LitecomZone::class,
            'beginnerForm' => BeginnerForm::class,
        ]);

        Carbon::macro('parseWithAppTimezone', fn($time) => Carbon::parse($time)->setTimezone(config('app.timezone')));

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return FrontendUrl::to('/reset-password', $user->locale) . '?token=' . $token;
        });
    }
}
