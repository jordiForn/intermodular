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
    Debug::log("Product update request initiated");
    
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        Debug::log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
        redirect('/admin/products.php')->with('error', 'Mètode de petició no vàlid')->send();
        exit;
    }
    
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
    
    // Create request object
    $request = new Request();
    
    // Validate CSRF token
    $submittedToken = $request->csrf_token ?? '';
    $storedToken = session()->get('csrf_token');
    
    if (empty($submittedToken) || empty($storedToken) || $submittedToken !== $storedToken) {
        Debug::log("CSRF token validation failed");
        $productId = $request->id ?? '';
        redirect("/productes/edit.php?id=$productId")
            ->with('error', 'Token de seguretat no vàlid. Si us plau, torna a intentar-ho.')
            ->send();
        exit;
    }
    
    Debug::log("CSRF token validation passed");
    
    // Use the controller to handle the update
    $controller = new ProducteController();
    $controller->update($request);
    
} catch (\Throwable $e) {
    Debug::log("Exception in update.php: " . $e->getMessage());
    Debug::log("Stack trace: " . $e->getTraceAsString());
    
    // Redirect with error message
    $productId = $request->id ?? '';
    redirect("/productes/edit.php?id=$productId")
        ->with('error', 'Error al actualitzar el producte: ' . $e->getMessage())
        ->send();
}

ob_end_flush();
?>
