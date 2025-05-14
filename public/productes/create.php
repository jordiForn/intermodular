<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\ProducteController;

try {
    (new ProducteController())->create();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
