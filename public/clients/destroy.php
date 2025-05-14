<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ClientController.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ClientController;

try {
    $id = (new Request())->id;
    (new ClientController())->destroy($id);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
