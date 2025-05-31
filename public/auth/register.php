<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/AuthController.php';
require_once __DIR__ . '/../../app/Http/Validators/RegisterValidator.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\AuthController;
use App\Http\Validators\RegisterValidator;

try {
    $request = new Request();
    
    // Apply middleware
    $response = $request->middleware([
        'csrf'
    ]);
    
    // If middleware returned a response, send it
    if ($response) {
        $response->send();
        exit;
    }
    
    // Validate registration data
    RegisterValidator::validate($request);
    
    // Process registration
    (new AuthController())->register($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
