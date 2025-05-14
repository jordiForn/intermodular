<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ClientController.php';
require_once __DIR__ . '/../../app/Http/Validators/ContactValidator.php';

use App\Core\Request;
use App\Core\ErrorHandler;
use App\Http\Controllers\ClientController;
use App\Http\Validators\ContactValidator;

try {
    $request = new Request();
    ContactValidator::validate($request);
    (new ClientController())->storeContact($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
