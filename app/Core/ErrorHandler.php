<?php
declare(strict_types=1);

namespace App\Core;
use Throwable;

class ErrorHandler
{
    public static function handle(Throwable $e)
    {
        // Always log the error
        $logPath = dirname(__DIR__, 2) . '/logs/error.log';
        $logDir = dirname($logPath);
        
        // Create logs directory if it doesn't exist
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        error_log($e->getMessage() . "\n" . $e->getTraceAsString(), 3, $logPath);
        
        // In development, show detailed error information
        if (defined('ENV') && ENV === 'development') {
            echo "<div style='background-color: #f8d7da; color: #721c24; padding: 20px; margin: 20px; border-radius: 5px; border: 1px solid #f5c6cb;'>";
            echo "<h1>Application Error</h1>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
            echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
            echo "<h2>Stack Trace:</h2>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            echo "</div>";
        } else {
            // In production, show a user-friendly error page
            http_response_code(500);
            
            // Check if view function exists before using it
            if (function_exists('view')) {
                try {
                    view('errors/500');
                } catch (Throwable $viewError) {
                    // Fallback if view function fails
                    echo "<h1>Server Error</h1>";
                    echo "<p>We're sorry, but something went wrong on our end.</p>";
                }
            } else {
                // Fallback if view function doesn't exist
                echo "<h1>Server Error</h1>";
                echo "<p>We're sorry, but something went wrong on our end.</p>";
            }
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
            $logPath = dirname(__DIR__, 2) . '/logs/error.log';
            error_log("HTTP Error $statusCode: $message", 3, $logPath);
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
        
        // Check if view function exists before using it
        if (function_exists('view')) {
            try {
                view($view, $data);
            } catch (Throwable $viewError) {
                // Fallback if view function fails
                echo "<h1>Error $statusCode</h1>";
                echo "<p>" . ($message ?? "An error occurred.") . "</p>";
            }
        } else {
            // Fallback if view function doesn't exist
            echo "<h1>Error $statusCode</h1>";
            echo "<p>" . ($message ?? "An error occurred.") . "</p>";
        }
        
        exit;
    }
}
