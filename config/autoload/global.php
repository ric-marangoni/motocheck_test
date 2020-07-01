<?php

return [
    'debug' => true,
    'whoops' => [
        'json_exceptions' => [
            'display'    => true,
            'show_trace' => true,
            'ajax_only'  => true,
        ],
    ],
    'app_url' => getenv('APP_URL'),
    'bugsnag' => [
        'key' => getenv('BUGSNAG_KEY'),
        'notify' => ['prod', 'dev', 'stage']
    ],
    'github_api' => [
        'http' => [
            'base_uri' => 'https://api.github.com',
            'connect_timeout' => 60,
            'auth' => ['ric-marangoni', '06b91f207b2209d1a7c2dc2c177dc9a6eb868c32'],
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]
    ]
];
