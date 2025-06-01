<?php
// filepath: d:\xampp\htdocs\Intermodular\public\admin\clients.php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Models\Client;
use App\Models\Comanda;

// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    Response::redirect('/auth/show-login.php', ['error' => 'Accés denegat. Has d\'iniciar sessió com a administrador.']);
    exit;
}

// Get all clients
$clients = Client::all();

// Add order count to each client (opcional, si lo usas en la vista)
foreach ($clients as $client) {
    $client->orderCount = Comanda::where('client_id', $client->id)->count();
}

// Start output buffering to capture the view content
ob_start();
include __DIR__ . '/../../resources/views/admin/clients.php';
$content = ob_get_clean();

// Set page title
$title = 'Gestió de Clients';

// Render using admin layout
include __DIR__ . '/../../resources/views/layouts/admin.php';