<?php

namespace App\Providers;

use BotMan\BotMan\BotMan;
use Illuminate\Support\ServiceProvider;
use Str;
use URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!app()->environment('local') || Str::startsWith(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }
        $this->app->singleton(BotMan::class, function ($app) {
            return $app->make('botman');
        });
    }
}
