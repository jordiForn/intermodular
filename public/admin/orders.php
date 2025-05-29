<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Models\Comanda;

// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    Response::redirect('/auth/show-login.php', ['error' => 'Accés denegat. Has d\'iniciar sessió com a administrador.']);
    exit;
}

// Get all orders
$orders = Comanda::orderBy('data_comanda', 'DESC')->get();

// Render the admin orders view
$title = 'Gestió de Comandes';
$content = view('admin/orders', [
    'orders' => $orders
]);

echo view('layouts/app', ['content' => $content, 'title' => $title]);
