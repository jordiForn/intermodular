<?php
ob_start();
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ClientController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Middleware.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\ClientController;
use App\Http\Middlewares\Middleware;

try {
    // Apply middleware to restrict access to authenticated users
    Middleware::auth();
    
    // Display list of clients
    (new ClientController())->index();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
ob_end_flush();