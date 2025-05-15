<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ComandaController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\ComandaController;

try {
    // Generate CSRF token for the checkout form
    \App\Http\Middlewares\Security\CsrfMiddleware::generateToken();
    
    // Show cart page
    (new ComandaController())->showCart();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
