<?php

// Enable error reporting during bootstrap
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define the project root directory
define('PROJECT_ROOT', dirname(__DIR__));

// Check if configuration files exist
$configFile = PROJECT_ROOT . '/config/config.php';
$dbConfigFile = PROJECT_ROOT . '/config/db.php';

if (!file_exists($configFile)) {
    die("Configuration file not found: $configFile");
}

if (!file_exists($dbConfigFile)) {
    die("Database configuration file not found: $dbConfigFile");
}

// Load configuration files
require_once $configFile;
require_once $dbConfigFile;

// Check if BASE_URL is defined
if (!defined('BASE_URL')) {
    die("BASE_URL is not defined in the configuration file");
}

// Define required files
$requiredFiles = [
    '/app/Core/Session.php',
    '/app/Core/Request.php',
    '/app/Core/Response.php',
    '/app/Core/DB.php',
    '/app/Core/Model.php',
    '/app/Core/QueryBuilder.php',
    '/app/Core/Auth.php',
    '/app/Core/ErrorHandler.php',
    '/app/Core/helpers.php',
    '/app/Exceptions/ModelNotFoundException.php',
    '/app/Http/Middlewares/Middleware.php',
    '/app/Http/Middlewares/MiddlewareHandler.php',
    '/app/Http/Middlewares/MiddlewareRegistry.php',
    '/app/Models/User.php',
    '/app/Models/Client.php',
    '/app/Models/Producte.php',
    '/app/Models/Comanda.php',
    '/app/Models/Servei.php',
    '/app/bootstrap/middleware.php'
];

// Check and load each required file
foreach ($requiredFiles as $file) {
    $filePath = PROJECT_ROOT . $file;
    if (!file_exists($filePath)) {
        die("Required file not found: $filePath");
    }
    require_once $filePath;
}

// Create logs directory if it doesn't exist
$logsDir = PROJECT_ROOT . '/logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}
