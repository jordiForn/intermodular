<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';

use App\Core\Request;
use App\Http\Controllers\ProducteController;
use App\Core\ErrorHandler;

try{
    $request = new Request();
    
    // Apply middleware specific to this route
    $response = $request->middleware([
        'cache' => ['type' => 'public', 'maxAge' => 300] // Cache for 5 minutes
    ]);
    
    // If middleware returned a response, send it
    if ($response) {
        $response->send();
        exit;
    }
    
    // Otherwise, continue to the controller
    (new ProducteController())->index($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
