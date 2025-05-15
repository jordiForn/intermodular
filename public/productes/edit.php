<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';

use App\Http\Middlewares\Middleware;
use App\Core\Request;
use App\Http\Controllers\ProducteController;
use App\Core\ErrorHandler;

try{
    Middleware::role(['admin']);
    $id = (new Request())->id;
    (new ProducteController())->edit($id);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
