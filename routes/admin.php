<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\CourtController;
use App\Http\Controllers\Admin\CourtTypeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\DowntimeController;
use App\Http\Controllers\Admin\Forms\BeginnerFormController;
use App\Http\Controllers\Admin\FutureMemberController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\IntervalController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LitecomZoneController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PlanCourtTypeRuleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\Users\UserBalanceEntryController;
use App\Http\Controllers\Admin\Users\UserController;
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

        Route::prefix('users')->group(function () {
            Route::get('all', [UserController::class, 'all']);
            Route::get('export', [UserController::class, 'export']);

            Route::apiResource('', UserController::class, ['parameters' => ['' => 'user']]);

            Route::get('{user}/balance-entries', [UserBalanceEntryController::class, 'allByUser']);
            Route::post('{user}/balance-entries', [UserBalanceEntryController::class, 'storeByUser']);
        });

        Route::get('/user-balance-entries', [UserBalanceEntryController::class, 'index']);

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

        Route::get('payments', [PaymentController::class, 'index']);

        Route::get('subscriptions', [SubscriptionController::class, 'index']);

        Route::controller(InvoiceController::class)->prefix('invoices')->group(function () {
            Route::get('', 'index');
            Route::get('export', 'export');
            Route::get('{invoice}/download', 'download');
        });

        Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
            Route::get('metrics', 'metrics');
            Route::get('incomes', 'incomes');
            Route::get('incomes-by-interval', 'incomesByInterval');
        });

        Route::get('reservations/calendar', [ReservationController::class, 'calendar']);
        Route::post('reservations/bulk-action', [ReservationController::class, 'bulkAction']);
        Route::apiResource('reservations', ReservationController::class)->except(['show', 'update']);
        Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);
        Route::post('reservations/{reservation}/cancel-all-same', [ReservationController::class, 'cancelAllSame']);
        Route::get('reservations/{reservation}/pay', [ReservationController::class, 'pay']);

        Route::get('future-members', [FutureMemberController::class, 'index']);

        Route::prefix('forms')->group(function () {
            Route::get('beginners', [BeginnerFormController::class, 'index']);;
        });

        Route::controller(LitecomZoneController::class)->prefix('litecom-zones')->group(function () {
            Route::get('', 'index');
            Route::post('sync', 'sync');
            Route::get('all', 'all');
            Route::put('{litecomZone}/on', 'on');
            Route::put('{litecomZone}/off', 'off');
            Route::get('{litecomZone}', 'show');
            Route::put('{litecomZone}', 'update');
        });

        Route::prefix('availability')->group(function () {
            Route::get('stats', [AvailabilityController::class, 'stats']);
            Route::get('stats-by-interval', [AvailabilityController::class, 'statsByInterval']);
        });

        Route::prefix('reports')->group(function () {
            Route::get('user-balances', [ReportController::class, 'userBalances']);
        });
    });
});
