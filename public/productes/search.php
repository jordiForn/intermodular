<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';

use App\Core\Request;
use App\Http\Controllers\ProducteController;
use App\Core\ErrorHandler;

try{
    $request = new Request();
    (new ProducteController())->search($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
