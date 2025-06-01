<?php
ob_start();
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/AuthController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\AuthController;

try {
    // Generate CSRF token for the login form
    \App\Http\Middlewares\Security\CsrfMiddleware::generateToken();
    
    // Create controller instance
    $controller = new AuthController();
    
    // Show login form
    $controller->showLoginForm();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
ob_end_flush();