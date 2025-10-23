<?php

return [
    // Paths where CORS should apply (API + Sanctum cookie endpoint if ever used)
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
    ],

    // Allow all HTTP methods or restrict if you want
    'allowed_methods' => ['*'],

    // Allowed origins come from env. Separate multiple with commas.
    // Examples for local dev have sensible defaults.
    'allowed_origins' => array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS',
        implode(',', [
            'http://localhost',
            'http://127.0.0.1',
            'http://localhost:3000',
            'http://127.0.0.1:3000',
            'http://localhost:5173', // Vite default
            'http://127.0.0.1:5173',
            'http://localhost:8000', // Laravel serve default
            'http://127.0.0.1:8000',
            env('FRONTEND_URL', ''),
        ])
    )))),

    // Wildcard patterns for origins (keep empty if not needed)
    'allowed_origins_patterns' => [],

    // Allow all headers by default
    'allowed_headers' => ['*'],

    // Headers exposed to the browser (usually not required)
    'exposed_headers' => [],

    // Preflight cache duration (seconds)
    'max_age' => 0,

    // If you plan to use cookie-based auth (Sanctum SPA), set to true.
    // For Bearer token auth (current default), this can remain false.
    'supports_credentials' => (bool) env('CORS_SUPPORTS_CREDENTIALS', false),
];
