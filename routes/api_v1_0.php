<?php

use App\Api\v1_0\ServerSyncController;

Route::post('ping', [ServerSyncController::class, 'ping'])
    ->name('ping');
Route::post('cpu', [ServerSyncController::class, 'cpu'])
    ->name('cpu');
