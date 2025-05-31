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
    
    // Get client ID from URL
    $id = (int)($request->id ?? 0);
    
    if (!$id) {
        http_error(400, 'ID de client no vàlid.');
        exit;
    }
    
    // Show client details
    (new ClientController())->show($id);
    
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
