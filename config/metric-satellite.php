<?php

return [
    // Endpoint path (under /api by default)
    'route' => [
        'prefix' => 'api/metrics',
        'name' => 'metrics.satellite',
        'middleware' => ['api', 'satellite.signature'],
    ],

    // Authentication
    'auth' => [
        // Option A: static token (simple)
        'token' => env('SATELLITE_TOKEN'),

        // Option B: signed HMAC header (recommended)
        'hmac' => [
            'enabled' => env('SATELLITE_HMAC_ENABLED', false),
            'key'     => env('SATELLITE_HMAC_KEY'),
            'algo'    => 'sha256',
            'header'  => 'X-Signature',
            'ts_header' => 'X-Signature-Timestamp',
            'skew_seconds' => 300,
        ],

        // Optional IP allow-list (CIDR or exact IPs)
        'allow_ips' => array_filter(explode(',', env('SATELLITE_ALLOW_IPS', ''))),
    ],
];
