<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Middleware.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\ProducteController;
use App\Http\Middlewares\Middleware;

try {
    Middleware::role(['1']); // Solo admin
    (new ProducteController())->create();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}