<?php
// Direct access route for product creation
// This file handles direct URL access to /productes/create.php

// Load the bootstrap to initialize the application
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Http\Controllers\ProducteController;
use App\Core\Request;
use App\Core\Debug;

try {
    Debug::log("Direct access to productes/create.php");
    
    // Create a new request instance
    $request = new Request();
    
    // Create controller instance and call the create method
    $controller = new ProducteController();
    $controller->create($request);
    
} catch (\Throwable $e) {
    Debug::log("Exception in direct access create.php: " . $e->getMessage());
    
    // Fallback error handling
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
?>
