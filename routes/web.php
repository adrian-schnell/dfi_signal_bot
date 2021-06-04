<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index']);

Route::match(['get', 'post'], 'botman', [BotController::class, 'handle']);
