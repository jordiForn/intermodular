<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../app/Http/Controllers/AuthController.php';

use App\Http\Controllers\AuthController;

(new AuthController())->showLoginForm();
