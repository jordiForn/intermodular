<?php
ob_start();
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/AuthController.php';
require_once __DIR__ . '/../../app/Http/Validators/LoginValidator.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\AuthController;
use App\Http\Validators\LoginValidator;

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
    
    // Validate login credentials
    LoginValidator::validate($request);
    
    // Process login
    (new AuthController())->login($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
ob_end_flush();