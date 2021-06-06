<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('learn_more', [PageController::class, 'learnMore'])->name('learn_more');

Route::match(['get', 'post'], 'botman', [BotController::class, 'handle']);
