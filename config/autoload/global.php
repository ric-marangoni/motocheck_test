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
            'auth' => ['ric-marangoni', '85ba240885f2425fe26b6ed7f146c378755c0c61'],
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]
    ]
];
