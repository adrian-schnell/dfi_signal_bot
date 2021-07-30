<?php

namespace App\Providers;

use BotMan\BotMan\BotMan;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!app()->environment('local')) {
            URL::forceScheme('https');
        }
        $this->app->singleton(BotMan::class, function ($app) {
            return $app->make('botman');
        });
    }

    public function boot(): void
    {
        //
    }
}
