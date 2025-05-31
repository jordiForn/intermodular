<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ServeiController.php';

use App\Http\Controllers\ServeiController;
use App\Core\ErrorHandler;

try {
    (new ServeiController())->getPiscines();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
