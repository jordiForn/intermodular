<?php

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use App\Core\ErrorHandler;

try {
    view('/resources/views/home/index.php');
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
