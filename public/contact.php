<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';
require_once __DIR__ . '/../app/Http/Controllers/ContactController.php';

use App\Core\ErrorHandler;
use App\Http\Controllers\ContactController;

try {
    (new ContactController())->showForm();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
