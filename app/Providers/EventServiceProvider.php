<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'App\Events\ProfileEvent' => [
            'App\Listeners\LogProfileEvent',
        ],
    ];
}
