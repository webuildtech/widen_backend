<?php


use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ContactUsController;
use App\Http\Controllers\User\CourtController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\PlanController;
use App\Http\Controllers\User\ReservationController;
use App\Http\Controllers\User\ReservationTimeController;
use App\Http\Controllers\User\SocialAuthController;
use App\Http\Controllers\User\SubscriptionController;

Route::prefix('courts')->group(function () {
    Route::get('', [CourtController::class, 'index']);
    Route::get('{court}', [CourtController::class, 'show']);

    Route::get('{court}/times', [CourtController::class, 'times']);
});

Route::post('/reservations', [ReservationController::class, 'store']);

Route::post('contact-us', [ContactUsController::class, 'store']);

Route::prefix('payments')->group(function () {
    Route::post('validate', [PaymentController::class, 'validate']);
    Route::post('callback', [PaymentController::class, 'callback']);
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('password-recovery', 'passwordRecovery');
    Route::post('password-reset', 'passwordReset');

    Route::post('social', SocialAuthController::class);
});

Route::middleware(['auth:user'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('account')->group(function () {
        Route::get('me', [AccountController::class, 'show']);
        Route::put('me', [AccountController::class, 'update']);
        Route::put('change-password', [AccountController::class, 'changePassword']);
        Route::post('top-up-balance', [AccountController::class, 'topUpBalance']);
    });

    Route::prefix('reservations')->group(function () {
        Route::get('', [ReservationController::class, 'index']);
        Route::post('pay', [ReservationController::class, 'pay']);
        Route::post('{reservation}/cancel', [ReservationController::class, 'cancel'])
            ->middleware('can:cancel,reservation');
    });

    Route::prefix('plans')->group(function () {
        Route::get('', [PlanController::class, 'index']);
    });

    Route::prefix('subscriptions')->group(function () {
        Route::get('current', [SubscriptionController::class, 'current']);
        Route::get('subscribe/{plan}', [SubscriptionController::class, 'subscribe']);
    });

    Route::prefix('payments')->group(function () {
       Route::get('', [PaymentController::class, 'index']);
       Route::get('{payment}/download-invoice', [PaymentController::class, 'downloadInvoice']);
    });
});
