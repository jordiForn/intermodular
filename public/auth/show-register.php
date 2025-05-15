<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/AuthController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\AuthController;

try {
    // Generate CSRF token for the registration form
    \App\Http\Middlewares\Security\CsrfMiddleware::generateToken();
    
    // Show registration form
    (new AuthController())->showRegisterForm();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
