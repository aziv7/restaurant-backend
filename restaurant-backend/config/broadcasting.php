<?php

return [

    'default' => env('BROADCAST_DRIVER', 'mercure'),

    'connections' => [

        // ...

        'mercure' => [
            'driver' => 'mercure',
            'url' => env('MERCURE_URL', 'http://localhost:3000/.well-known/mercure'),
            'secret' => env('MERCURE_SECRET', 'aVerySecretKey'),
        ],

    ],

];
