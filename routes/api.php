<?php


use App\Http\Controllers\User\CourtController;
use App\Http\Controllers\User\ReservationController;


Route::prefix('courts')->group(function () {
    Route::get('', [CourtController::class, 'index']);
    Route::get('{court}', [CourtController::class, 'show']);

    Route::get('{court}/times', [CourtController::class, 'times']);
});

Route::post('/reservations', [ReservationController::class, 'store']);
