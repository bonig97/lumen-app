<?php

return [

    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/lumen.log'),
            'level' => 'debug',
            'days' => 1,
        ],

        'access' => [
            'driver' => 'single',
            'path' => storage_path('logs/access.log'),
            'level' => 'info',
        ],
    ],
];
