<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Validators/ProducteValidator.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';

use App\Core\Request;
use App\Http\Middlewares\Middleware;
use App\Http\Validators\ProducteValidator;
use App\Http\Controllers\ProducteController;
use App\Core\ErrorHandler;

try{
    Middleware::role(['admin']);
    $request = new Request();
    ProducteValidator::validate($request);
    (new ProducteController())->update($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
