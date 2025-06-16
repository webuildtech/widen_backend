<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CourtController;
use App\Http\Controllers\Admin\CourtTypeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\DowntimeController;
use App\Http\Controllers\Admin\FutureMemberController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\IntervalController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PlanCourtTypeRuleController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
    });

    Route::middleware(['auth:admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        Route::prefix('account')->group(function () {
            Route::get('me', [AccountController::class, 'show']);
        });

        Route::apiResource('admins', AdminController::class);

        Route::get('users/all', [UserController::class, 'all']);
        Route::apiResource('users', UserController::class);

        Route::get('plans/all', [PlanController::class, 'all']);
        Route::apiResource('plans', PlanController::class);

        Route::apiResource('plan-court-type-rules', PlanCourtTypeRuleController::class)->except(['store', 'destroy']);

        Route::get('groups/all', [GroupController::class, 'all']);
        Route::apiResource('groups', GroupController::class);

        Route::get('intervals/all', [IntervalController::class, 'all']);
        Route::apiResource('intervals', IntervalController::class);

        Route::get('court-types/all', [CourtTypeController::class, 'all']);

        Route::get('courts/all', [CourtController::class, 'all']);
        Route::apiResource('courts', CourtController::class);

        Route::apiResource('downtimes', DowntimeController::class);

        Route::apiResource('discount-codes', DiscountCodeController::class);

        Route::get('subscriptions', [SubscriptionController::class, 'index']);

        Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
            Route::get('metrics', 'metrics');
            Route::get('incomes', 'incomes');
            Route::get('incomes-by-interval', 'incomesByInterval');
        });

        Route::get('reservations/calendar', [ReservationController::class, 'calendar']);
        Route::apiResource('reservations', ReservationController::class)->except(['show', 'update']);
        Route::get('reservations/{reservation}/pay', [ReservationController::class, 'pay']);

        Route::get('future-members', [FutureMemberController::class, 'index']);
    });
});
