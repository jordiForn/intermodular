<?php
ob_start();
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';

use App\Core\Request;
use App\Http\Controllers\ProducteController;
use App\Core\ErrorHandler;
use App\Core\Debug;
use App\Core\Auth;

try {
    Debug::log("Product edit request initiated");
    
    // Check authentication and admin role
    if (!Auth::check()) {
        Debug::log("User not authenticated");
        redirect('/auth/show-login.php?error=unauthorized')->send();
        exit;
    }
    
    if (!Auth::isAdmin()) {
        Debug::log("User is not admin");
        http_response_code(403);
        view('errors.403');
        exit;
    }
    
    $request = new Request();
    $id = $request->id;
    
    if (empty($id) || !is_numeric($id)) {
        Debug::log("Invalid product ID: " . ($id ?? 'null'));
        redirect('/admin/products.php')->with('error', 'ID de producte no vàlid')->send();
        exit;
    }
    
    Debug::log("Editing product with ID: $id");
    
    // Use the controller to handle the edit
    $controller = new ProducteController();
    $controller->edit($id);
    
} catch (\Throwable $e) {
    Debug::log("Exception in edit.php: " . $e->getMessage());
    Debug::log("Stack trace: " . $e->getTraceAsString());
    
    // Redirect with error message
    redirect('/admin/products.php')
        ->with('error', 'Error al carregar el producte per editar: ' . $e->getMessage())
        ->send();
}

ob_end_flush();
?>
