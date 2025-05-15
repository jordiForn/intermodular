<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ServeiController.php';

use App\Core\Request;
use App\Http\Controllers\ServeiController;
use App\Core\ErrorHandler;

try {
    $id = (new Request())->id;
    (new ServeiController())->show($id);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
