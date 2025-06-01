<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Http\Controllers\ProducteController;
use App\Core\Request;
use App\Core\Auth;

// Ensure user is authenticated and has admin privileges
if (!Auth::check() || !Auth::isAdmin()) {
    header('Location: ' . BASE_URL . '/auth/show-login.php?error=unauthorized');
    exit;
}

// Create controller instance and handle the request
$controller = new ProducteController();
$request = new Request();

// Call the store method
$controller->store($request);
?>
