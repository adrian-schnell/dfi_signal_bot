<?php

use App\Api\v1_0\MasternodeHealthWebhookController;
use App\Api\v1_0\ServerSyncController;

Route::post('ping', [ServerSyncController::class, 'ping'])
    ->name('ping');
Route::post('webhook/masternode_health', [MasternodeHealthWebhookController::class, 'receiveWebhook'])
    ->name('webhook.masternode_health');
