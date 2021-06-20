<?php

use App\Api\v1_0\ServerSyncController;

Route::post('ping', [ServerSyncController::class, 'ping'])
    ->name('ping');
Route::post('block-info', [ServerSyncController::class, 'blockInfo'])
    ->name('block-info');
Route::post('server-stats', [ServerSyncController::class, 'serverStats'])
    ->name('server-stats');
