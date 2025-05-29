<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Models\Comanda;
use App\Models\Client;

// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    Response::redirect('/auth/show-login.php', ['error' => 'Accés denegat. Has d\'iniciar sessió com a administrador.']);
    exit;
}

// Get all orders with the most recent first
$orders = Comanda::orderBy('data_comanda', 'DESC')->get();

// Calculate order statistics
$totalOrders = count($orders);
$pendingOrders = 0;
$completedOrders = 0;
$cancelledOrders = 0;
$totalRevenue = 0;

foreach ($orders as $order) {
    $totalRevenue += $order->import_total;
    
    if ($order->estat === 'pendent') {
        $pendingOrders++;
    } elseif ($order->estat === 'completada') {
        $completedOrders++;
    } elseif ($order->estat === 'cancel·lada') {
        $cancelledOrders++;
    }
}

// Get recent orders (last 5)
$recentOrders = array_slice($orders, 0, 5);

// Start output buffering to capture the view content
ob_start();
include __DIR__ . '/../../resources/views/admin/orders.php';
$content = ob_get_clean();

// Set page title
$title = 'Gestió de Comandes';

// Render using admin layout
include __DIR__ . '/../../resources/views/layouts/admin.php';
