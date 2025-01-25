<?php

$env_path = __DIR__ . '/../../.env';

if (file_exists($env_path)) {
    $env = parse_ini_file($env_path);
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
} else {
    echo ".env file not found.\n";
}

return [
    'db' => [
        'host' => getenv('DB_HOST'),
        'port' => getenv('DB_PORT'),
        'dbname' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
    ],
    'mail' => [
        'from' => '',
    ],
    'app' => [
        'app_name' => getenv('APP_NAME'),
    ],
];
