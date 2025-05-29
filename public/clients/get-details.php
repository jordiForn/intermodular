<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Models\Client;
use App\Models\Comanda;

// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Accés denegat']);
    exit;
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de client no proporcionat']);
    exit;
}

$clientId = $_GET['id'];
$client = Client::find($clientId);

if (!$client) {
    echo json_encode(['success' => false, 'message' => 'Client no trobat']);
    exit;
}

// Get client orders
$orders = Comanda::where('client_id', $clientId)->orderBy('data_comanda', 'DESC')->get();

// Generate HTML for client details
$html = '
<div class="row">
    <div class="col-md-6">
        <h4>Informació del Client</h4>
        <table class="table">
            <tr>
                <th>ID:</th>
                <td>' . $client->id . '</td>
            </tr>
            <tr>
                <th>Nom:</th>
                <td>' . htmlspecialchars($client->nom) . '</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>' . htmlspecialchars($client->email) . '</td>
            </tr>
            <tr>
                <th>Telèfon:</th>
                <td>' . htmlspecialchars($client->telefon) . '</td>
            </tr>
            <tr>
                <th>Adreça:</th>
                <td>' . htmlspecialchars($client->adreca) . '</td>
            </tr>
            <tr>
                <th>Data de registre:</th>
                <td>' . date('d/m/Y', strtotime($client->created_at)) . '</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h4>Comandes del Client</h4>';

if (count($orders) > 0) {
    $html .= '
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Import</th>
                    <th>Estat</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($orders as $order) {
        $statusClass = 'bg-success';
        $statusText = 'Completada';
        
        if ($order->estat === 'pendent') {
            $statusClass = 'bg-warning text-dark';
            $statusText = 'Pendent';
        } elseif ($order->estat === 'cancel·lada') {
            $statusClass = 'bg-danger';
            $statusText = 'Cancel·lada';
        }
        
        $html .= '
                <tr>
                    <td>' . $order->id . '</td>
                    <td>' . date('d/m/Y', strtotime($order->data_comanda)) . '</td>
                    <td>' . number_format($order->import_total, 2) . ' €</td>
                    <td><span class="badge ' . $statusClass . '">' . $statusText . '</span></td>
                </tr>';
    }
    
    $html .= '
            </tbody>
        </table>';
} else {
    $html .= '<p class="text-muted">Aquest client no té comandes.</p>';
}

$html .= '
    </div>
</div>';

echo json_encode(['success' => true, 'html' => $html]);
