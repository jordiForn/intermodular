<?php

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use App\Core\ErrorHandler;

try {
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    // Check if bootstrap loaded correctly
    if (!defined('BASE_URL')) {
        throw new Exception('Configuration not loaded properly');
    }
    
    view('home.index');
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
