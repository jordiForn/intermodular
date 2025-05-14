<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ClientController.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ClientController;

try {
    $request = new Request();
    (new ClientController())->update($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
