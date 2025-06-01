<?php
ob_start();
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ClientController.php';
require_once __DIR__ . '/../../app/Http/Validators/ClientValidator.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Middleware.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';
\App\Core\Debug::log('INICIO update.php CLIENTES');
use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ClientController;
use App\Http\Validators\ClientValidator;
use App\Http\Middlewares\Middleware;

try {
    
    $request = new Request();
    
    // Apply middleware to restrict access to admin users and verify CSRF token
    $response = $request->middleware([
        'role' => ['admin'],
        'csrf'
    ]);
    
    // If middleware returned a response, send it
    if ($response) {
        $response->send();
        exit;
    }
    
    // Validate client data
    ClientValidator::validate($request);
    
    // Process client update
    (new ClientController())->update($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
ob_end_flush();