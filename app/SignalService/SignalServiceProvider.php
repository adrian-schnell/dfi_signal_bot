<?php

namespace App\SignalService;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SignalServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(SignalService::class, function (Application $app) {
		    return new SignalService(app('botman'));
        });
	}
}
