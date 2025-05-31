<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Models\Comanda;
use App\Models\Client;
use App\Models\Producte;

// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Accés denegat']);
    exit;
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de comanda no proporcionat']);
    exit;
}

$orderId = $_GET['id'];
$order = Comanda::find($orderId);

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Comanda no trobada']);
    exit;
}

// Get client information
$client = Client::find($order->client_id);
$clientName = $client ? $client->nom : 'Client desconegut';

// Get order products
$orderProducts = json_decode($order->productes, true) ?? [];

// Generate HTML for order details
$html = '
<div class="row">
    <div class="col-md-6">
        <h4>Informació de la Comanda</h4>
        <table class="table">
            <tr>
                <th>ID:</th>
                <td>' . $order->id . '</td>
            </tr>
            <tr>
                <th>Client:</th>
                <td>' . htmlspecialchars($clientName) . '</td>
            </tr>
            <tr>
                <th>Data:</th>
                <td>' . date('d/m/Y', strtotime($order->data_comanda)) . '</td>
            </tr>
            <tr>
                <th>Import Total:</th>
                <td>' . number_format($order->import_total, 2) . ' €</td>
            </tr>
            <tr>
                <th>Estat:</th>
                <td>';

// Determine status class and text
$statusClass = 'bg-success';
$statusText = 'Completada';

if ($order->estat === 'pendent') {
    $statusClass = 'bg-warning text-dark';
    $statusText = 'Pendent';
} elseif ($order->estat === 'cancel·lada') {
    $statusClass = 'bg-danger';
    $statusText = 'Cancel·lada';
}

$html .= '<span class="badge ' . $statusClass . '">' . $statusText . '</span>
                </td>
            </tr>
            <tr>
                <th>Adreça d\'enviament:</th>
                <td>' . htmlspecialchars($order->adreca_enviament) . '</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h4>Productes</h4>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Producte</th>
                    <th>Quantitat</th>
                    <th>Preu</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>';

$totalItems = 0;
foreach ($orderProducts as $productId => $quantity) {
    $product = Producte::find($productId);
    if ($product) {
        $subtotal = $product->preu * $quantity;
        $html .= '
                <tr>
                    <td>' . htmlspecialchars($product->nom) . '</td>
                    <td>' . $quantity . '</td>
                    <td>' . number_format($product->preu, 2) . ' €</td>
                    <td>' . number_format($subtotal, 2) . ' €</td>
                </tr>';
        $totalItems += $quantity;
    }
}

$html .= '
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total:</th>
                    <th>' . number_format($order->import_total, 2) . ' €</th>
                </tr>
            </tfoot>
        </table>
        <p><strong>Total d\'articles:</strong> ' . $totalItems . '</p>
    </div>
</div>';

echo json_encode(['success' => true, 'html' => $html]);
