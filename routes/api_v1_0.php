<?php

use App\Api\v1_0\MasternodeHealthWebhookController;
use App\Api\v1_0\ServerSyncController;
use App\Api\v1_0\StatisticController;

Route::post('ping', [ServerSyncController::class, 'ping'])
    ->name('ping');
Route::get('statistics/all', [StatisticController::class, 'getAll'])
    ->name('statistics.all');
Route::get('statistics/last_week', [StatisticController::class, 'getLastWeek'])
    ->name('statistics.last_week');
Route::post('webhook/masternode_health', [MasternodeHealthWebhookController::class, 'receiveWebhook'])
    ->name('webhook.masternode_health');
