<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';

use App\Core\Request;
use App\Http\Controllers\ProducteController;

try {
    $request = new Request();
    (new ProducteController())->updateStock($request);
} catch (\Throwable $e) {
    redirect('/admin/products.php')->with('error', 'Error al actualitzar l\'estoc: ' . $e->getMessage())->send();
}