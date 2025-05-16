<?php

// Enable error reporting during bootstrap
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define the project root directory - use absolute path to avoid path resolution issues
define('PROJECT_ROOT', realpath(dirname(__DIR__)));

// Create logs directory if it doesn't exist
$logsDir = PROJECT_ROOT . DIRECTORY_SEPARATOR . 'logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}

// Start a bootstrap log - ensure we have a valid path
$bootstrapLog = $logsDir . DIRECTORY_SEPARATOR . 'bootstrap.log';
if (empty($bootstrapLog)) {
    // Fallback to a temporary file if path is empty
    $bootstrapLog = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'intermodular_bootstrap.log';
}

// Initialize bootstrap log
file_put_contents($bootstrapLog, "=== Bootstrap Started at " . date('Y-m-d H:i:s') . " ===\n");

function bootstrap_log($message) {
    global $bootstrapLog;
    if (!empty($bootstrapLog)) {
        file_put_contents($bootstrapLog, "[" . date('Y-m-d H:i:s') . "] $message\n", FILE_APPEND);
    }
}

// Check if configuration files exist
$configFile = PROJECT_ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
$dbConfigFile = PROJECT_ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db.php';

bootstrap_log("Checking config files...");
if (!file_exists($configFile)) {
    bootstrap_log("ERROR: Configuration file not found: $configFile");
    die("Configuration file not found: $configFile");
}

if (!file_exists($dbConfigFile)) {
    bootstrap_log("ERROR: Database configuration file not found: $dbConfigFile");
    die("Database configuration file not found: $dbConfigFile");
}

// Load configuration files
bootstrap_log("Loading config files...");
require_once $configFile;
require_once $dbConfigFile;

// Check if BASE_URL is defined
if (!defined('BASE_URL')) {
    bootstrap_log("ERROR: BASE_URL is not defined in the configuration file");
    die("BASE_URL is not defined in the configuration file");
}

bootstrap_log("BASE_URL is defined as: " . BASE_URL);

// Define required core files
$coreFiles = [
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Debug.php',
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Session.php',
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Request.php',
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Response.php',
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'DB.php',
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Model.php',
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'QueryBuilder.php',
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Auth.php',
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'ErrorHandler.php',
    'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'helpers.php',
];

// Load core files first
bootstrap_log("Loading core files...");
foreach ($coreFiles as $file) {
    $filePath = PROJECT_ROOT . DIRECTORY_SEPARATOR . $file;
    if (!file_exists($filePath)) {
        bootstrap_log("ERROR: Required core file not found: $filePath");
        die("Required core file not found: $filePath");
    }
    require_once $filePath;
    bootstrap_log("Loaded: $file");
}

// Create and load the Auth facade
$authFacadeDir = PROJECT_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Facades';
if (!is_dir($authFacadeDir)) {
    mkdir($authFacadeDir, 0755, true);
    bootstrap_log("Created Facades directory: $authFacadeDir");
}

$authFacadeFile = $authFacadeDir . DIRECTORY_SEPARATOR . 'Auth.php';
if (!file_exists($authFacadeFile)) {
    bootstrap_log("Creating Auth facade file: $authFacadeFile");
    $authFacadeContent = '<?php
declare(strict_types=1);

/**
 * Auth Facade - Provides a global access point to Auth functionality
 * This allows using Auth:: syntax in views without namespace
 */
class Auth extends \App\Core\Auth
{
    // This class inherits all methods from App\Core\Auth
    // It exists to provide a global access point without namespace
}
';
    file_put_contents($authFacadeFile, $authFacadeContent);
}

bootstrap_log("Loading Auth facade...");
require_once $authFacadeFile;
bootstrap_log("Loaded Auth facade");

// Initialize debugging - do this after loading Debug.php and helpers.php
bootstrap_log("Initializing debug system...");
try {
    \App\Core\Debug::init(true);
    bootstrap_log("Debug system initialized successfully");
    \App\Core\Debug::log("Debug system initialized");
    bootstrap_log("Debug log file: " . \App\Core\Debug::getLogFilePath());
} catch (\Throwable $e) {
    bootstrap_log("ERROR initializing debug system: " . $e->getMessage());
    bootstrap_log("Stack trace: " . $e->getTraceAsString());
    // Continue without debugging
}

// Define other required files
$requiredFiles = [
    'app' . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'ModelNotFoundException.php',
    'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Middlewares' . DIRECTORY_SEPARATOR . 'Middleware.php',
    'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Middlewares' . DIRECTORY_SEPARATOR . 'MiddlewareHandler.php',
    'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Middlewares' . DIRECTORY_SEPARATOR . 'MiddlewareRegistry.php',
    'app' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'User.php',
    'app' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Client.php',
    'app' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Producte.php',
    'app' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Comanda.php',
    'app' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Servei.php',
];

// Check and load each required file
bootstrap_log("Loading other required files...");
foreach ($requiredFiles as $file) {
    $filePath = PROJECT_ROOT . DIRECTORY_SEPARATOR . $file;
    if (!file_exists($filePath)) {
        bootstrap_log("ERROR: Required file not found: $filePath");
        \App\Core\Debug::log("Required file not found: $filePath");
        die("Required file not found: $filePath");
    }
    require_once $filePath;
    bootstrap_log("Loaded: $file");
}

// Load middleware bootstrap last
bootstrap_log("Loading middleware bootstrap...");
$middlewareBootstrap = PROJECT_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'middleware.php';
if (!file_exists($middlewareBootstrap)) {
    bootstrap_log("ERROR: Middleware bootstrap file not found: $middlewareBootstrap");
    \App\Core\Debug::log("Middleware bootstrap file not found: $middlewareBootstrap");
    die("Middleware bootstrap file not found: $middlewareBootstrap");
}
require_once $middlewareBootstrap;
bootstrap_log("Loaded middleware bootstrap");

// Set a flag to indicate bootstrap was loaded successfully
define('BOOTSTRAP_LOADED', true);

bootstrap_log("Bootstrap completed successfully");
\App\Core\Debug::log("Bootstrap completed successfully");
