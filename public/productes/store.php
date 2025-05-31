<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';
require_once __DIR__ . '/../../app/Http/Validators/ProducteValidator.php';

use App\Core\Request;
use App\Http\Controllers\ProducteController;
use App\Http\Validators\ProducteValidator;
use App\Core\ErrorHandler;

try {
    $request = new Request();
    
    // Apply middleware specific to this route
    $response = $request->middleware([
        'role' => ['admin'],
        'csrf'
    ]);
    
    // If middleware returned a response, send it
    if ($response) {
        $response->send();
        exit;
    }
    
    // Otherwise, continue to the controller
    ProducteValidator::validate($request);
    (new ProducteController())->store($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
