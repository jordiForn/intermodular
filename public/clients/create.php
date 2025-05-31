<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ClientController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Middleware.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ClientController;
use App\Http\Middlewares\Middleware;

try {
    $request = new Request();
    
    // Apply middleware to restrict access to admin users
    $response = $request->middleware([
        'role' => ['admin']
    ]);
    
    // If middleware returned a response, send it
    if ($response) {
        $response->send();
        exit;
    }
    
    // Show create form
    (new ClientController())->create();
    
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
