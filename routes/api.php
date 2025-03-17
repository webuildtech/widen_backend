<?php


use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ContactUsController;
use App\Http\Controllers\User\CourtController;
use App\Http\Controllers\User\PlanController;
use App\Http\Controllers\User\ReservationController;
use App\Http\Controllers\User\ReservationTimeController;

Route::prefix('courts')->group(function () {
    Route::get('', [CourtController::class, 'index']);
    Route::get('{court}', [CourtController::class, 'show']);

    Route::get('{court}/times', [CourtController::class, 'times']);
});

Route::post('/reservations', [ReservationController::class, 'store']);

Route::post('contact-us', [ContactUsController::class, 'store']);

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('password-recovery', 'passwordRecovery');
    Route::post('password-reset', 'passwordReset');
});

Route::middleware(['auth:user'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('account')->group(function () {
        Route::get('me', [AccountController::class, 'show']);
        Route::put('me', [AccountController::class, 'update']);
        Route::put('change-password', [AccountController::class, 'changePassword']);
    });

    Route::prefix('reservation-times')->group(function () {
        Route::get('', [ReservationTimeController::class, 'index']);
        Route::post('{reservationTime}/cancel', [ReservationTimeController::class, 'cancel'])
            ->middleware('can:cancel,reservationTime');
    });

    Route::prefix('plans')->group(function () {
       Route::get('', [PlanController::class, 'index']);
    });
});
