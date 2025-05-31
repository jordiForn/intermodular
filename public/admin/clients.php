<?php
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

// Add order count to each client
foreach ($clients as $client) {
    $client->orderCount = Comanda::where('client_id', $client->id)->count();
}

// Render the admin clients view
$title = 'Gestió de Clients';
$content = view('admin/clients', [
    'clients' => $clients
]);

echo view('layouts/app', ['content' => $content, 'title' => $title]);
