<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/AuthController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Middleware.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\AuthController;
use App\Http\Middlewares\Middleware;

try {
    // Ensure user is authenticated before logging out
    Middleware::auth();
    
    // Process logout
    (new AuthController())->logout();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
