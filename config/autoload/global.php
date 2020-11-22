<?php

declare(strict_types=1);

return [
    'weather_api' => [
        'base_uri' => 'http://api.weatherapi.com/v1/',
        'key' => getenv('WEATHER_API_KEY'),
    ],
    'musement-api' => [
        'base_uri' => 'https://api.musement.com/api/v3/',
        'timeout' => 2,
    ],
    'normalization' => [
        'metadata_directories' => [
            __DIR__ . '/../../normalization'
        ],
    ]
];
