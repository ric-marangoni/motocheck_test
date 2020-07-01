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
            'auth' => ['ric-marangoni', '423e727c45ca40cc11d6b5f9335d0f09185e8455'],
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]
    ]
];
