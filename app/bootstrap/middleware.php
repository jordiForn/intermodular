<?php

use App\Http\Middlewares\MiddlewareRegistry;
use App\Http\Middlewares\Security\CsrfMiddleware;

// Generate a CSRF token for the session if it doesn't exist
if (class_exists('App\Http\Middlewares\Security\CsrfMiddleware')) {
    CsrfMiddleware::getToken();
}

// Register additional middleware if needed
// MiddlewareRegistry::register('custom', CustomMiddleware::class);

// Add or remove global middleware
// MiddlewareRegistry::addGlobal('logger');
// MiddlewareRegistry::removeGlobal('sanitize');
