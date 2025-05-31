<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Middleware.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ProducteController;
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
    
    // Get product details
    (new ProducteController())->getProductDetails($request);
} catch (Throwable $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
    exit;
}
