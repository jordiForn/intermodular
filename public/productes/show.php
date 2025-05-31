<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';

use App\Core\Request;
use App\Http\Controllers\ProducteController;
use App\Core\ErrorHandler;

try {
    $id = (new Request())->id;
    (new ProducteController())->show($id);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
