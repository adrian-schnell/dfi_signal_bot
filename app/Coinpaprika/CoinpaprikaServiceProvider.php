<?php

namespace App\Coinpaprika;

use CoinpaprikaApi;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CoinpaprikaServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(CoinpaprikaApi::class, function (Application $app) {
		    return new CoinpaprikaApi();
        });
	}
}
