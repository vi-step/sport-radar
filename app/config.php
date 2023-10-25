<?php
return [
    'database' => [
        'host' => $_ENV['MYSQL_HOST'],
        'port' => $_ENV['MYSQL_PORT'],
        'user' => $_ENV['MYSQL_USER'],
        'password' => $_ENV['MYSQL_PASSWORD'],
        'dbname' => $_ENV['MYSQL_DATABASE'],
        'driver' => $_ENV['APP_DB_DRIVER'],
    ],
    'debug' => in_array(strtolower($_ENV['APP_DEBUG'] ?? ''), ['1', 'true', 'yes', 'y', 'on']),
];
