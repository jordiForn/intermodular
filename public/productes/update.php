<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ProducteController.php';
require_once __DIR__ . '/../../app/Http/Validators/ProducteValidator.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ProducteController;
use App\Http\Validators\ProducteValidator;

try {
    $request = new Request();
    ProducteValidator::validate($request);
    (new ProducteController())->update($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
