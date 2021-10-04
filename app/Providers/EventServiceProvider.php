<?php

namespace App\Providers;

use App\Events\MnEnabledEvent;
use App\Events\MnPreResignedEvent;
use App\Events\MnResignedEvent;
use App\Listeners\MnEnabledListener;
use App\Listeners\MnPreResignedListener;
use App\Listeners\MnResignedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MnEnabledEvent::class     => [
            MnEnabledListener::class,
        ],
        MnPreResignedEvent::class => [
            MnPreResignedListener::class,
        ],
        MnResignedEvent::class    => [
            MnResignedListener::class,
        ],
    ];
}
