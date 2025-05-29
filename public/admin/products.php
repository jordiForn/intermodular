<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Models\Producte;

// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    Response::redirect('/auth/show-login.php', ['error' => 'Accés denegat. Has d\'iniciar sessió com a administrador.']);
    exit;
}

// Get all products
$products = Producte::all();

// Render the admin products view
$title = 'Gestió de Productes';
$content = view('admin/products', [
    'products' => $products
]);

echo view('layouts/app', ['content' => $content, 'title' => $title]);
