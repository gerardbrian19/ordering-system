<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;

/**
 * Returns a shared Monolog logger instance for the given channel.
 * Channels write to logs/{channel}.log with 30-day rotation.
 *
 * Available channels:
 *   'app'      — database, mail, and general application errors
 *   'security' — authentication events (login success/fail, CSRF failures)
 *
 * Log level is controlled by the LOG_LEVEL env variable (default: 'debug').
 */
function getLogger(string $channel = 'app'): Logger
{
    static $loggers = [];

    if (!isset($loggers[$channel])) {
        $level  = Level::fromName(defined('LOG_LEVEL') ? LOG_LEVEL : 'debug');
        $logger = new Logger($channel);
        $logger->pushHandler(
            new RotatingFileHandler(
                __DIR__ . '/../logs/' . $channel . '.log',
                30,
                $level
            )
        );
        $loggers[$channel] = $logger;
    }

    return $loggers[$channel];
}
