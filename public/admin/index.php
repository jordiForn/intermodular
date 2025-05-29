<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Debug;

try {
    // Check if user is authenticated and is admin
    if (!Auth::check()) {
        Debug::log("Unauthenticated user trying to access admin dashboard");
        session()->setFlash('error', 'Has d\'iniciar sessió per accedir al panell d\'administració');
        session()->setFlash('redirect_to', '/admin/');
        redirect('/auth/show-login.php')->send();
        exit;
    }
    
    if (!Auth::isAdmin()) {
        Debug::log("Non-admin user trying to access admin dashboard: " . Auth::user()->username);
        session()->setFlash('error', 'No tens permisos per accedir al panell d\'administració');
        redirect('/')->send();
        exit;
    }
    
    // Log admin dashboard access
    Debug::log("Admin user accessing dashboard: " . Auth::user()->username);
    
    // Load the admin dashboard view
    view('admin.index');
    
} catch (\Throwable $e) {
    Debug::log("Exception in admin dashboard: " . $e->getMessage());
    Debug::log("Stack trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo "Error loading admin dashboard: " . $e->getMessage();
}
?>
