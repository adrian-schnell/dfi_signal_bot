<?php

namespace App\SignalService;

use App\Http\Service\TelegramMessageService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SignalServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(SignalService::class, function (Application $app) {
            $botman = app('botman');

            return new SignalService($botman, new TelegramMessageService($botman));
        });
	}
}
