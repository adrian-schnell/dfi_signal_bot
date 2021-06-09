<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use PhpJunior\NovaLogViewer\Tool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return true;
        });
    }

    protected function cards(): array
    {
        return [];
    }

    protected function dashboards(): array
    {
        return [];
    }

    public function tools(): array
    {
        return [
            new Tool(),
        ];
    }

    public function register(): void
    {
        //
    }
}
