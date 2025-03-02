<?php


use App\Http\Controllers\User\CourtController;
use App\Http\Controllers\User\ReservationController;

Route::get('/courts/times', [CourtController::class, 'times']);

Route::prefix('courts')->group(function () {
    Route::get('', [CourtController::class, 'index']);
    Route::get('{court}', [CourtController::class, 'show']);
});

Route::post('/reservations', [ReservationController::class, 'store']);
