<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\CourtController;
use App\Http\Controllers\Admin\IntervalController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function(){
    Route::post('login', 'login');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('account')->group(function () {
        Route::get('me', [AccountController::class, 'show']);
    });

    Route::apiResource('courts', CourtController::class);

    Route::get('intervals/all', [IntervalController::class, 'all']);
    Route::apiResource('intervals', IntervalController::class);
});
