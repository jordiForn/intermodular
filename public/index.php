<?php

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use App\Core\ErrorHandler;

try {
    view('home.index');
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
