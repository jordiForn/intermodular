<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

// Check if the script is being run from the command line
$isCli = php_sapi_name() === 'cli';

// Function to output messages
function output($message, $isError = false) {
    global $isCli;
    
    if ($isCli) {
        echo ($isError ? "ERROR: " : "SUCCESS: ") . $message . PHP_EOL;
    } else {
        echo '<div style="padding: 10px; margin: 5px; ' . 
             ($isError ? 'background-color: #ffdddd; color: #990000;' : 'background-color: #ddffdd; color: #009900;') . 
             '">' . ($isError ? "ERROR: " : "SUCCESS: ") . htmlspecialchars($message) . '</div>';
    }
}

// Start the verification
if (!$isCli) {
    echo '<h1>Path Verification</h1>';
    echo '<p>This script verifies that all paths in the application are correctly set.</p>';
}

// Check if PROJECT_ROOT is defined
if (!defined('PROJECT_ROOT')) {
    output('PROJECT_ROOT is not defined.', true);
} else {
    output('PROJECT_ROOT is defined as: ' . PROJECT_ROOT);
    
    // Check if the directory exists
    if (!is_dir(PROJECT_ROOT)) {
        output('PROJECT_ROOT directory does not exist: ' . PROJECT_ROOT, true);
    } else {
        output('PROJECT_ROOT directory exists.');
    }
}

// Check if BASE_URL is defined
if (!defined('BASE_URL')) {
    output('BASE_URL is not defined.', true);
} else {
    output('BASE_URL is defined as: ' . BASE_URL);
}

// Check if HOME is defined
if (!defined('HOME')) {
    output('HOME is not defined.', true);
} else {
    output('HOME is defined as: ' . HOME);
}

// Check key directories
$directories = [
    'app' => PROJECT_ROOT . '/app',
    'config' => PROJECT_ROOT . '/config',
    'resources' => PROJECT_ROOT . '/resources',
    'public' => PROJECT_ROOT . '/public',
    'bootstrap' => PROJECT_ROOT . '/bootstrap',
    'views' => PROJECT_ROOT . '/resources/views',
    'models' => PROJECT_ROOT . '/app/Models',
    'controllers' => PROJECT_ROOT . '/app/Http/Controllers',
    'middlewares' => PROJECT_ROOT . '/app/Http/Middlewares',
];

foreach ($directories as $name => $path) {
    if (!is_dir($path)) {
        output("Directory '$name' does not exist: $path", true);
    } else {
        output("Directory '$name' exists.");
    }
}

// Check key files
$files = [
    'config.php' => PROJECT_ROOT . '/config/config.php',
    'db.php' => PROJECT_ROOT . '/config/db.php',
    'bootstrap.php' => PROJECT_ROOT . '/bootstrap/bootstrap.php',
    'helpers.php' => PROJECT_ROOT . '/app/Core/helpers.php',
    'Response.php' => PROJECT_ROOT . '/app/Core/Response.php',
    'Request.php' => PROJECT_ROOT . '/app/Core/Request.php',
    'ErrorHandler.php' => PROJECT_ROOT . '/app/Core/ErrorHandler.php',
];

foreach ($files as $name => $path) {
    if (!file_exists($path)) {
        output("File '$name' does not exist: $path", true);
    } else {
        output("File '$name' exists.");
    }
}

// Check if logs directory is writable
$logsDir = PROJECT_ROOT . '/logs';
if (!is_dir($logsDir)) {
    // Try to create it
    if (!mkdir($logsDir, 0755, true)) {
        output("Logs directory does not exist and could not be created: $logsDir", true);
    } else {
        output("Logs directory created: $logsDir");
    }
} else {
    if (!is_writable($logsDir)) {
        output("Logs directory is not writable: $logsDir", true);
    } else {
        output("Logs directory is writable.");
    }
}

// Test the project_path helper function
if (function_exists('project_path')) {
    $testPath = project_path('test/path');
    output("project_path('test/path') returns: $testPath");
} else {
    output("project_path function does not exist.", true);
}

// Test the asset helper function
if (function_exists('asset')) {
    $testAsset = asset('css/styles.css');
    output("asset('css/styles.css') returns: $testAsset");
} else {
    output("asset function does not exist.", true);
}

if (!$isCli) {
    echo '<h2>Verification Complete</h2>';
}
