<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set a custom error handler for this test
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "<div style='color: red; background-color: #ffeeee; padding: 10px; margin: 10px 0; border: 1px solid #ffaaaa;'>";
    echo "<strong>PHP Error:</strong> [$errno] $errstr<br>";
    echo "<strong>File:</strong> $errfile<br>";
    echo "<strong>Line:</strong> $errline<br>";
    echo "</div>";
    
    // Also log to PHP's error log
    error_log("PHP Error: [$errno] $errstr in $errfile on line $errline");
    
    // Don't execute the PHP internal error handler
    return true;
});

// Function to safely load the bootstrap file
function safe_require($file) {
    try {
        if (!file_exists($file)) {
            echo "<div style='color: red; background-color: #ffeeee; padding: 10px; margin: 10px 0; border: 1px solid #ffaaaa;'>";
            echo "<strong>Error:</strong> File not found: $file";
            echo "</div>";
            return false;
        }
        
        require_once $file;
        return true;
    } catch (\Throwable $e) {
        echo "<div style='color: red; background-color: #ffeeee; padding: 10px; margin: 10px 0; border: 1px solid #ffaaaa;'>";
        echo "<strong>Error loading file:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
        echo "<strong>File:</strong> " . htmlspecialchars($file) . "<br>";
        echo "</div>";
        return false;
    }
}

// Try to load the bootstrap file - use realpath to resolve the path correctly
$bootstrapFile = realpath(__DIR__ . '/../bootstrap/bootstrap.php');
if (!$bootstrapFile) {
    $bootstrapFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'bootstrap.php';
}

// Initialize the bootstrapLoaded variable before using it
$bootstrapLoaded = false;

// Try to load the bootstrap file
if ($bootstrapFile) {
    $bootstrapLoaded = safe_require($bootstrapFile);
} else {
    echo "<div style='color: red; background-color: #ffeeee; padding: 10px; margin: 10px 0; border: 1px solid #ffaaaa;'>";
    echo "<strong>Error:</strong> Could not resolve bootstrap file path";
    echo "</div>";
}

// Check if BOOTSTRAP_LOADED constant is defined
if (defined('BOOTSTRAP_LOADED')) {
    $bootstrapLoaded = true;
}

// Function to check if a file exists and is readable
function check_file($path) {
    $exists = file_exists($path);
    $readable = $exists ? is_readable($path) : false;
    $result = [
        'path' => $path,
        'exists' => $exists,
        'readable' => $readable,
        'size' => $exists ? filesize($path) : 0,
        'modified' => $exists ? date('Y-m-d H:i:s', filemtime($path)) : 'N/A',
    ];
    
    // Try to use Debug::log if available
    if ($GLOBALS['bootstrapLoaded'] && class_exists('\\App\\Core\\Debug')) {
        try {
            \App\Core\Debug::log("File check: $path", $result);
        } catch (\Throwable $e) {
            error_log("Error using Debug::log: " . $e->getMessage());
        }
    }
    
    return $result;
}

// Function to check a directory
function check_directory($path) {
    $exists = is_dir($path);
    $readable = $exists ? is_readable($path) : false;
    $result = [
        'path' => $path,
        'exists' => $exists,
        'readable' => $readable,
    ];
    
    if ($exists && $readable) {
        $files = scandir($path);
        $result['files'] = array_diff($files, ['.', '..']);
    }
    
    // Try to use Debug::log if available
    if ($GLOBALS['bootstrapLoaded'] && class_exists('\\App\\Core\\Debug')) {
        try {
            \App\Core\Debug::log("Directory check: $path", $result);
        } catch (\Throwable $e) {
            error_log("Error using Debug::log: " . $e->getMessage());
        }
    }
    
    return $result;
}

// Start the test
echo "<h1>View Rendering Test Results</h1>";

if (!$bootstrapLoaded) {
    echo "<div style='color: red; background-color: #ffeeee; padding: 10px; margin: 10px 0; border: 1px solid #ffaaaa;'>";
    echo "<strong>Critical Error:</strong> Failed to load bootstrap file: $bootstrapFile";
    echo "</div>";
} else {
    echo "<div style='color: green; background-color: #eeffee; padding: 10px; margin: 10px 0; border: 1px solid #aaffaa;'>";
    echo "<strong>Success:</strong> Bootstrap file loaded successfully";
    echo "</div>";
    
    // Check if Debug class is available
    if (class_exists('\\App\\Core\\Debug')) {
        echo "<div style='color: green; background-color: #eeffee; padding: 10px; margin: 10px 0; border: 1px solid #aaffaa;'>";
        echo "<strong>Success:</strong> Debug class is available";
        echo "</div>";
        
        // Check the log file path
        $logFilePath = \App\Core\Debug::getLogFilePath();
        echo "<div style='background-color: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc;'>";
        echo "<strong>Debug Log File:</strong> " . ($logFilePath ?? 'Not set');
        if ($logFilePath) {
            echo "<br><strong>Exists:</strong> " . (file_exists($logFilePath) ? 'Yes' : 'No');
            echo "<br><strong>Writable:</strong> " . (is_writable(dirname($logFilePath)) ? 'Yes' : 'No');
        }
        echo "</div>";
    } else {
        echo "<div style='color: red; background-color: #ffeeee; padding: 10px; margin: 10px 0; border: 1px solid #ffaaaa;'>";
        echo "<strong>Error:</strong> Debug class is not available";
        echo "</div>";
    }
}

try {
    // Check critical directories
    $projectRoot = function_exists('project_path') ? project_path() : dirname(__DIR__);
    $viewsDir = $projectRoot . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
    $layoutsDir = $projectRoot . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts';
    $homeDir = $projectRoot . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'home';
    
    $directories = [
        'project_root' => check_directory($projectRoot),
        'views' => check_directory($viewsDir),
        'layouts' => check_directory($layoutsDir),
        'home' => check_directory($homeDir),
    ];
    
    // Check critical files
    $layoutFile = $projectRoot . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'app.php';
    $homeIndexFile = $projectRoot . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR . 'index.php';
    $testFile = $projectRoot . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'test.php';
    
    $files = [
        'layout' => check_file($layoutFile),
        'home_index' => check_file($homeIndexFile),
        'test' => check_file($testFile),
    ];
    
    // Output the test results
    echo "<h2>Directory Checks</h2>";
    echo "<ul>";
    foreach ($directories as $name => $dir) {
        echo "<li><strong>$name</strong>: " . ($dir['exists'] ? "Exists" : "Missing") . 
             " | " . ($dir['readable'] ? "Readable" : "Not readable");
        
        if ($dir['exists'] && $dir['readable'] && !empty($dir['files'])) {
            echo "<ul>";
            foreach ($dir['files'] as $file) {
                echo "<li>$file</li>";
            }
            echo "</ul>";
        }
        
        echo "</li>";
    }
    echo "</ul>";
    
    echo "<h2>File Checks</h2>";
    echo "<ul>";
    foreach ($files as $name => $file) {
        echo "<li><strong>$name</strong>: " . ($file['exists'] ? "Exists" : "Missing") . 
             " | " . ($file['readable'] ? "Readable" : "Not readable") .
             " | Size: " . $file['size'] . " bytes" .
             " | Modified: " . $file['modified'];
        echo "</li>";
    }
    echo "</ul>";
    
    // Test simple view rendering if bootstrap loaded successfully
    if ($bootstrapLoaded) {
        echo "<h2>Test View Content</h2>";
        
        if (class_exists('\\App\\Core\\Response') && method_exists('\\App\\Core\\Response', 'loadView')) {
            try {
                echo "<p>Attempting to load test view content directly...</p>";
                $testContent = \App\Core\Response::loadView('test', ['message' => 'Direct view load test']);
                
                echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
                echo $testContent;
                echo "</div>";
                
                echo "<div style='color: green; background-color: #eeffee; padding: 10px; margin: 10px 0; border: 1px solid #aaffaa;'>";
                echo "<strong>Success:</strong> Test view loaded successfully";
                echo "</div>";
            } catch (\Throwable $e) {
                echo "<div style='color: red; background-color: #ffeeee; padding: 10px; margin: 10px 0; border: 1px solid #ffaaaa;'>";
                echo "<strong>Error loading test view:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
                echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . "<br>";
                echo "<strong>Line:</strong> " . $e->getLine() . "<br>";
                echo "</div>";
            }
            
            echo "<h2>Full View Rendering Test</h2>";
            echo "<p>Now attempting to render the test view with layout...</p>";
            
            try {
                // Try to render the full view
                \App\Core\Response::view('test', ['message' => 'Full view rendering test']);
                
                echo "<div style='color: green; background-color: #eeffee; padding: 10px; margin: 10px 0; border: 1px solid #aaffaa;'>";
                echo "<strong>Success:</strong> Full view rendering completed successfully";
                echo "</div>";
            } catch (\Throwable $e) {
                echo "<div style='color: red; background-color: #ffeeee; padding: 10px; margin: 10px 0; border: 1px solid #ffaaaa;'>";
                echo "<strong>Error rendering full view:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
                echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . "<br>";
                echo "<strong>Line:</strong> " . $e->getLine() . "<br>";
                echo "<h3>Stack Trace:</h3>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                echo "</div>";
            }
        } else {
            echo "<div style='color: red; background-color: #ffeeee; padding: 10px; margin: 10px 0; border: 1px solid #ffaaaa;'>";
            echo "<strong>Error:</strong> Response class or loadView method not available";
            echo "</div>";
        }
    }
    
} catch (\Throwable $e) {
    echo "<div style='color: white; background-color: #cc0000; padding: 20px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h2>Test Failed</h2>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

// Restore the default error handler
restore_error_handler();
