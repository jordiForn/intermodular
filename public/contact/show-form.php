<?php

require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/ContactController.php';
require_once __DIR__ . '/../../app/Http/Middlewares/Security/CsrfMiddleware.php';

use App\Http\Controllers\ContactController;
use App\Core\ErrorHandler;
use App\Http\Middlewares\Security\CsrfMiddleware;

try {
    // Generate CSRF token for the contact form
    CsrfMiddleware::generateToken();
    
    // Show contact form
    (new ContactController())->showForm();
} catch (Throwable $e) {
    ErrorHandler::handle($e);
}
