<?php

return [
    'guards' => [
        'client' => [
            'driver' => 'session',
            'provider' => 'clients'
        ]
    ],

    'providers' => [
        'clients' => [
            'driver' => 'eloquent',
            'model' => \Modules\ClientManager\App\Models\Client::class,
        ],
    ],
];
