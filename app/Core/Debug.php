<?php
declare(strict_types=1);

namespace App\Core;

class Debug
{
    private static $logFile = null;
    private static $enabled = false;
    private static $startTime = null;
    private static $memoryUsage = null;
    private static $initialized = false;
    
    public static function init(bool $enabled = true): void
    {
        self::$enabled = $enabled;
        self::$startTime = microtime(true);
        self::$memoryUsage = memory_get_usage();
        self::$initialized = true;
        
        if ($enabled) {
            // Define a fallback log path in case project_path() fails
            $fallbackLogPath = dirname(__DIR__, 2) . '/logs/debug.log';
            
            try {
                // Try to use project_path first
                if (function_exists('project_path')) {
                    self::$logFile = project_path('logs/debug.log');
                } else {
                    self::$logFile = $fallbackLogPath;
                }
            } catch (\Throwable $e) {
                // If project_path throws an exception, use the fallback
                self::$logFile = $fallbackLogPath;
            }
            
            // Ensure the log directory exists
            $logDir = dirname(self::$logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            // Verify the log file is writable
            if (!is_writable($logDir)) {
                // If not writable, try to use the system temp directory
                self::$logFile = sys_get_temp_dir() . '/intermodular_debug.log';
                error_log("Warning: Log directory not writable. Using temp directory: " . self::$logFile);
            }
            
            // Clear the log file
            file_put_contents(self::$logFile, "=== Debug Log Started at " . date('Y-m-d H:i:s') . " ===\n");
        }
    }
    
    public static function log(string $message, array $context = []): void
    {
        // Ensure the class is initialized
        if (!self::$initialized) {
            self::init();
        }
        
        if (!self::$enabled) {
            return;
        }
        
        // Double-check that we have a valid log file
        if (self::$logFile === null) {
            // Emergency fallback - use PHP's error_log
            error_log("Debug::log called but no log file is set. Message: $message");
            return;
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $elapsed = round((microtime(true) - self::$startTime) * 1000, 2);
        $memory = round((memory_get_usage() - self::$memoryUsage) / 1024, 2);
        
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = isset($backtrace[1]) ? 
            basename($backtrace[1]['file']) . ':' . $backtrace[1]['line'] : 
            'unknown';
        
        $contextStr = '';
        if (!empty($context)) {
            $contextStr = " | Context: " . json_encode($context, JSON_UNESCAPED_SLASHES);
        }
        
        $logMessage = "[{$timestamp}] [{$elapsed}ms] [{$memory}KB] [{$caller}] {$message}{$contextStr}\n";
        
        try {
            file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
        } catch (\Throwable $e) {
            // If file_put_contents fails, use PHP's error_log as a fallback
            error_log("Failed to write to debug log: " . $e->getMessage());
            error_log("Original message: $logMessage");
        }
    }
    
    public static function dump($var, string $label = null): void
    {
        if (!self::$initialized) {
            self::init();
        }
        
        if (!self::$enabled) {
            return;
        }
        
        $output = ($label ? "{$label}: " : '') . var_export($var, true);
        self::log($output);
    }
    
    public static function traceView(string $view, string $stage, array $data = []): void
    {
        if (!self::$initialized) {
            self::init();
        }
        
        if (!self::$enabled) {
            return;
        }
        
        self::log("View Rendering [{$stage}]: {$view}", [
            'data_keys' => array_keys($data),
            'memory' => memory_get_usage(),
        ]);
    }
    
    public static function traceFile(string $file, bool $exists): void
    {
        if (!self::$initialized) {
            self::init();
        }
        
        if (!self::$enabled) {
            return;
        }
        
        self::log("File Check: {$file}", [
            'exists' => $exists,
            'readable' => $exists ? is_readable($file) : false,
        ]);
    }
    
    // Get the current log file path - useful for debugging
    public static function getLogFilePath(): ?string
    {
        return self::$logFile;
    }
}
