<?php
require_once __DIR__ . '/../../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Core\Request;
use App\Models\User;
use App\Core\Debug;

// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    Response::redirect('/auth/show-login.php', ['error' => 'Accés denegat. Has d\'iniciar sessió com a administrador.']);
    exit;
}

$request = new Request();

// Get user ID from request
$id = (int)$request->id;
// Find user
$user = User::find($id);

// Check if user exists
if (!$user) {
    redirect('/admin/users/index.php')->with('error', 'Usuari no trobat.')->send();
    exit;
}

// Prevent deleting yourself
if ($user->id === Auth::user()->id) {
    redirect('/admin/users/index.php')->with('error', 'No pots eliminar el teu propi compte.')->send();
    exit;
}

try {
    // Delete user
    if ($user->delete()) {
        // Redirect to users list with success message
        redirect('/admin/users/index.php')->with('success', 'Usuari eliminat correctament.')->send();
    } else {
        // Redirect back with error
        redirect('/admin/users/index.php')->with('error', 'Error en eliminar l\'usuari.')->send();
    }
} catch (\Exception $e) {
    Debug::log("Error deleting user: " . $e->getMessage());
    redirect('/admin/users/index.php')->with('error', 'Error en eliminar l\'usuari: ' . $e->getMessage())->send();
}
