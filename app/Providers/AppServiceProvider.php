<?php

namespace App\Providers;

use BotMan\BotMan\BotMan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BotMan::class, function($app){
            return $app->make('botman');
        });
    }

    public function boot(): void
    {
        //
    }
}
