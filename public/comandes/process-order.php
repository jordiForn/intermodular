<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ComandaController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Middleware.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ComandaController;
use App\Http\Middlewares\Middleware;

try {
    $request = new Request();
    
    // Apply middleware to ensure user is authenticated and verify CSRF token
    $response = $request->middleware([
        'auth',
        'csrf'
    ]);
    
    // If middleware returned a response, send it
    if ($response) {
        $response->send();
        exit;
    }
    
    // Process the order
    (new ComandaController())->processOrder($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
