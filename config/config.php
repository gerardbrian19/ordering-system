<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'])->notEmpty()->except(['DB_PASS']);

// Database credentials
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// App settings
define('APP_NAME', $_ENV['APP_NAME'] ?? 'ShopEase');
define('APP_URL',  $_ENV['APP_URL']  ?? 'http://localhost:8000');
