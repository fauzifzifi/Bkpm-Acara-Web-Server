<?php
namespace App\Core;

use Throwable;

class Logger
{
    public static function error(Throwable $exception): void
    {
        $entry = sprintf(
            "[%s] %s: %s in %s:%d\n%s\n\n",
            date('Y-m-d H:i:s'),
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
        $directory = dirname(__DIR__, 2) . '/storage/logs';

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            error_log('Unable to create application log directory.' . PHP_EOL . $entry);
            return;
        }

        if (file_put_contents($directory . '/app.log', $entry, FILE_APPEND | LOCK_EX) === false) {
            error_log('Unable to write application log file.' . PHP_EOL . $entry);
        }
    }
}
