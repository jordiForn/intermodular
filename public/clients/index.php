<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ClientController.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\ClientController;

try {
    (new ClientController())->index();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
