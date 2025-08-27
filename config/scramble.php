<?php

use Dedoc\Scramble\Http\Middleware\RestrictedDocsAccess;

return [
    'api_path' => 'api',
    'api_domain' => null,
    'export_path' => 'api.json',

    'info' => [
        'version' => env('API_VERSION', '0.0.1'),
        'description' => 'Nepal Volleyball League API Documentation',
    ],

    'ui' => [
        'enabled' => true,
        'path' => 'docs',
    ],

    'servers' => null,
    'enum_cases_description_strategy' => 'description',
    'middleware' => ['web'],

    /*
     * UPDATED AUTH CONFIGURATION FOR SANCTUM
     */
    'auth' => [
        'enabled' => true,
        'type' => 'http', // This is important for Bearer token
        'scheme' => 'bearer',
        'bearerFormat' => 'JWT', // or 'token' 
        'description' => 'Enter your Sanctum bearer token',
        'flows' => [
            'bearerAuth' => [
                'type' => 'http',
                'scheme' => 'bearer',
                'bearerFormat' => 'JWT',
            ]
        ]
    ],

    'extensions' => [],
];
