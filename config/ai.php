<?php

return [
    'openrouter' => [
        'api_key' => env('OPENROUTER_API_KEY'),
        'model' => env('OPENROUTER_MODEL', 'google/gemma-2-9b-it:free'),
        'site_url' => env('APP_URL'),
        'site_name' => env('APP_NAME'),
    ],
];
