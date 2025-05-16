<?php

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start output buffering to catch any early output
ob_start();

try {
    // Load the bootstrap file
    $bootstrapFile = __DIR__ . '/../bootstrap/bootstrap.php';
    
    if (!file_exists($bootstrapFile)) {
        throw new Exception("Bootstrap file not found: $bootstrapFile");
    }
    
    require_once $bootstrapFile;
    
    // Check if Debug class is available
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Index.php: Bootstrap loaded successfully");
    }
    
    // Check if view function is available
    if (!function_exists('view')) {
        throw new Exception("The 'view' function is not defined. Check if helpers.php is loaded correctly.");
    }
    
    // Check if the home view exists
    $homeViewPath = dirname(__DIR__) . '/resources/views/home/index.php';
    if (!file_exists($homeViewPath)) {
        throw new Exception("Home view file not found: $homeViewPath");
    }
    
    // Render the home view
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Index.php: Rendering home view");
    }
    
    view('home.index');
    
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Index.php: View rendered successfully");
    }
    
} catch (Throwable $e) {
    // Clean the output buffer
    ob_clean();
    
    // Log the error if Debug class is available
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("ERROR in index.php: " . $e->getMessage());
        \App\Core\Debug::log("Stack trace: " . $e->getTraceAsString());
    }
    
    // Log the error to a file
    $logDir = dirname(__DIR__) . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $errorLog = $logDir . '/error.log';
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . "\n";
    $errorMessage .= "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    $errorMessage .= "Stack trace:\n" . $e->getTraceAsString() . "\n\n";
    
    file_put_contents($errorLog, $errorMessage, FILE_APPEND);
    
    // Display a user-friendly error message
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Application Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .error-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .error-header {
            background-color: #cc0000;
            color: white;
            padding: 10px 20px;
            margin: -20px -20px 20px -20px;
            border-radius: 5px 5px 0 0;
        }
        .error-details {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error-trace {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: monospace;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class='error-container'>
        <div class='error-header'>
            <h1>Application Error</h1>
        </div>
        
        <div class='error-details'>
            <h2>Error Details</h2>
            <p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>
            <p><strong>Line:</strong> " . $e->getLine() . "</p>
        </div>
        
        <h2>Stack Trace</h2>
        <div class='error-trace'>
            <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
        </div>
        
        <p>Please check the error log for more details.</p>
    </div>
</body>
</html>";
}

// End output buffering
ob_end_flush();
