<?php

use App\Api\v1_0\ServerSyncController;

Route::get('cpu', [ServerSyncController::class, 'cpu'])
    ->name('cpu');
