<?php
// Simple debug script to check PHP functionality and configuration

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>PHP Debug Information</h1>";

// Check PHP version
echo "<h2>PHP Version</h2>";
echo "<p>" . phpversion() . "</p>";

// Check loaded extensions
echo "<h2>Loaded Extensions</h2>";
echo "<pre>";
print_r(get_loaded_extensions());
echo "</pre>";

// Check include path
echo "<h2>Include Path</h2>";
echo "<p>" . get_include_path() . "</p>";

// Check if bootstrap file exists
echo "<h2>Bootstrap File</h2>";
$bootstrapPath = __DIR__ . '/../bootstrap/bootstrap.php';
echo "<p>Path: " . $bootstrapPath . "</p>";
echo "<p>Exists: " . (file_exists($bootstrapPath) ? 'Yes' : 'No') . "</p>";

// Check if config file exists
echo "<h2>Config File</h2>";
$configPath = __DIR__ . '/../config/config.php';
echo "<p>Path: " . $configPath . "</p>";
echo "<p>Exists: " . (file_exists($configPath) ? 'Yes' : 'No') . "</p>";

// Check document root
echo "<h2>Document Root</h2>";
echo "<p>" . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Check current script
echo "<h2>Current Script</h2>";
echo "<p>" . $_SERVER['SCRIPT_FILENAME'] . "</p>";

// Check server software
echo "<h2>Server Software</h2>";
echo "<p>" . $_SERVER['SERVER_SOFTWARE'] . "</p>";

// Try to include bootstrap file and check for errors
echo "<h2>Bootstrap Include Test</h2>";
try {
    require_once $bootstrapPath;
    echo "<p>Bootstrap included successfully</p>";
    echo "<p>BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'Not defined') . "</p>";
} catch (Throwable $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
