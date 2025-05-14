<?php
declare(strict_types=1);

namespace App\Core;
use Throwable;

class ErrorHandler
{
    public static function handle(Throwable $e)
    {
        if (ENV === 'development') {
            echo "<pre>";
            echo "Error: {$e->getMessage()} \n";
            echo "Archivo: {$e->getFile()} \n";
            echo "Línea: {$e->getLine()} \n";
            echo "Stacktrace: \n{$e->getTraceAsString()}";
            echo "</pre>";
        } else {
            error_log($e->getMessage(), 3, __DIR__ . '/../../logs/error.log');
            view('errors/500.php');
        }
    }
}
