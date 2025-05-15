<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ComandaController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Middleware.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\ComandaController;
use App\Http\Middlewares\Middleware;

try {
    // Ensure user is authenticated
    Middleware::auth();
    
    // Generate CSRF token for the checkout form
    \App\Http\Middlewares\Security\CsrfMiddleware::generateToken();
    
    // Show checkout form
    (new ComandaController())->showCheckoutForm();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
