<?php

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\PDOMySql\Driver::class,
                'host' => getenv('DATABASE_HOST'),
                'port' => getenv('DATABASE_PORT'),
                'user' => getenv('DATABASE_USER'),
                'password' => getenv('DATABASE_PASSWORD'),
                'dbname' => getenv('DATABASE_NAME'),
                'charset' => 'utf8',
                'driverOptions' => [
                    1002 => "SET NAMES 'UTF8'"
                ],
            ]
        ]
    ]
];