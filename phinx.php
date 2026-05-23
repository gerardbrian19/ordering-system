<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds'      => 'db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment'     => 'local',
        'local' => [
            'adapter' => 'mysql',
            'host'    => $_ENV['DB_HOST'],
            'name'    => $_ENV['DB_NAME'],
            'user'    => $_ENV['DB_USER'],
            'pass'    => $_ENV['DB_PASS'] ?? '',
            'port'    => '3306',
            'charset' => 'utf8mb4',
        ],
    ],
    'version_order' => 'creation',
];
