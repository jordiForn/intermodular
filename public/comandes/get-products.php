<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Models\Comanda;
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

// Get order products
$orderProducts = json_decode($order->productes, true) ?? [];

// Generate HTML for products
$html = '<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Imatge</th>
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
                <td>
                    <img src="' . BASE_URL . '/images/' . $product->imatge . '" 
                         alt="' . htmlspecialchars($product->nom) . '" 
                         class="img-thumbnail" style="max-width: 50px;">
                </td>
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
                <th colspan="4">Total:</th>
                <th>' . number_format($order->import_total, 2) . ' €</th>
            </tr>
        </tfoot>
    </table>
</div>';

echo json_encode(['success' => true, 'html' => $html]);
