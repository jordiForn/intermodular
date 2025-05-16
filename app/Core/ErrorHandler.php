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
            error_log($e->getMessage(), 3, project_path('logs/error.log'));
            view('errors/500');
        }
    }

    /**
     * Handle HTTP errors with appropriate status codes
     * 
     * @param int $statusCode The HTTP status code
     * @param string|null $message Optional custom message
     * @return void
     */
    public static function handleHttpError(int $statusCode, ?string $message = null)
    {
        http_response_code($statusCode);
        
        // Log the error if it's server-side (5xx)
        if ($statusCode >= 500) {
            error_log("HTTP Error $statusCode: $message", 3, project_path('logs/error.log'));
        }
        
        // Map status codes to view files
        $errorViews = [
            400 => 'errors/400',
            403 => 'errors/403',
            404 => 'errors/404',
            500 => 'errors/500',
        ];
        
        // Default to 500 if no specific view exists
        $view = $errorViews[$statusCode] ?? 'errors/500';
        
        // Pass the custom message if provided
        $data = [];
        if ($message) {
            $data['customMessage'] = $message;
        }
        
        view($view, $data);
        exit;
    }
}
