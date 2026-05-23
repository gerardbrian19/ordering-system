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
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Goldcomm');
define('APP_URL',  $_ENV['APP_URL']  ?? 'http://localhost:8000');

// SMTP / email settings (optional — app won't break if absent)
define('SMTP_HOST',      $_ENV['SMTP_HOST']      ?? 'smtp.gmail.com');
define('SMTP_PORT',      (int)($_ENV['SMTP_PORT'] ?? 587));
define('SMTP_USER',      $_ENV['SMTP_USER']       ?? '');
define('SMTP_PASS',      $_ENV['SMTP_PASS']       ?? '');
define('SMTP_FROM',      $_ENV['SMTP_FROM']       ?? '');
define('SMTP_FROM_NAME', $_ENV['SMTP_FROM_NAME']  ?? APP_NAME);
