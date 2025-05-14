<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/VotoController.php';
require_once __DIR__ . '/../../app/Http/Validators/VotoValidator.php';

use App\Http\Middlewares\Middleware;
use App\Core\Request;
use App\Http\Validators\VotoValidator;
use App\Http\Controllers\VotoController;
use App\Core\ErrorHandler;

try {
    Middleware::auth();
    $request = new Request();
    VotoValidator::validate($request);
    (new VotoController())->store($request);
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}