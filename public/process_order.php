<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';
require_once __DIR__ . '/../app/Http/Controllers/ComandaController.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ComandaController;

try {
    $request = new Request();
    (new ComandaController())->processOrder($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
