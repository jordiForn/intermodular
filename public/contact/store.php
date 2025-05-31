<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ContactController.php';
require_once __DIR__ . '/../../app/Http/Validators/ContactValidator.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ContactController;
use App\Http\Validators\ContactValidator;
use App\Http\Middlewares\Middleware;

try {
    $request = new Request();
    
    // Apply middleware to verify CSRF token
    $response = $request->middleware([
        'csrf'
    ]);
    
    // If middleware returned a response, send it
    if ($response) {
        $response->send();
        exit;
    }
    
    // Process contact form submission
    (new ContactController())->store($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
