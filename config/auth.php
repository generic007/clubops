<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'agents'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'agents',
        ],
        'player' => [
            'driver' => 'session',
            'provider' => 'players',
        ],
    ],

    'providers' => [
        'agents' => [
            'driver' => 'eloquent',
            'model' => App\Models\Agent::class,
        ],
        'players' => [
            'driver' => 'eloquent',
            'model' => App\Models\Player::class,
        ],
    ],

    'passwords' => [
        'agents' => [
            'provider' => 'agents',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'players' => [
            'provider' => 'players',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
