<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ComandaController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Middleware.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ComandaController;
use App\Http\Middlewares\Middleware;

try {
    // Ensure user is authenticated
    Middleware::auth();
    
    $request = new Request();
    $id = $request->id;
    
    // Show confirmation page
    (new ComandaController())->Confirmation($id);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
