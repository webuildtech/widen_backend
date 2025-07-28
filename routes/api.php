<?php

use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ContactUsController;
use App\Http\Controllers\User\CourtController;
use App\Http\Controllers\User\CourtTypeController;
use App\Http\Controllers\User\DiscountCodeController;
use App\Http\Controllers\User\FutureMemberController;
use App\Http\Controllers\User\InvoiceController;
use App\Http\Controllers\User\NewsletterController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\PlanController;
use App\Http\Controllers\User\PlanCourtTypeRuleController;
use App\Http\Controllers\User\ReservationController;
use App\Http\Controllers\User\SocialAuthController;
use App\Http\Controllers\User\SubscriptionController;

Route::get('court-types', [CourtTypeController::class, 'index']);

Route::get('plan-court-type-rules', [PlanCourtTypeRuleController::class, 'index']);

Route::prefix('courts')->group(function () {
    Route::get('', [CourtController::class, 'index']);
});

Route::post('/discount-codes/check', [DiscountCodeController::class, 'check']);

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
        Route::post('', [ReservationController::class, 'store']);
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
        Route::post('subscribe/{plan}', [SubscriptionController::class, 'subscribe']);
    });

    Route::prefix('payments')->group(function () {
       Route::get('', [PaymentController::class, 'index']);
    });

    Route::prefix('invoices')->group(function () {
        Route::get('', [InvoiceController::class, 'index']);
        Route::get('{invoice}/download', [InvoiceController::class, 'download']);
    });
});

Route::post('future-members', [FutureMemberController::class, 'store']);

Route::post('newsletter', NewsletterController::class);
