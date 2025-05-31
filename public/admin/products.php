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

// Get product statistics
$totalProducts = count($products);
$lowStockCount = 0;
$outOfStockCount = 0;
$totalValue = 0;

foreach ($products as $product) {
    $totalValue += $product->preu * $product->stock;
    
    if ($product->stock <= 5 && $product->stock > 0) {
        $lowStockCount++;
    } elseif ($product->stock == 0) {
        $outOfStockCount++;
    }
}

// Get product categories
$categories = [];
foreach ($products as $product) {
    if (!empty($product->categoria) && !in_array($product->categoria, $categories)) {
        $categories[] = $product->categoria;
    }
}

// Start output buffering to capture the view content
ob_start();
include __DIR__ . '/../../resources/views/admin/products.php';
$content = ob_get_clean();

// Set page title
$title = 'Gestió de Productes';

// Render using admin layout
include __DIR__ . '/../../resources/views/layouts/admin.php';
