<?php

// Define the project root directory
define('PROJECT_ROOT', dirname(__DIR__));

// Load configuration files
require_once PROJECT_ROOT . '/config/config.php';
require_once PROJECT_ROOT . '/config/db.php';

// Core classes
require_once PROJECT_ROOT . '/app/Core/Session.php';
require_once PROJECT_ROOT . '/app/Core/Request.php';
require_once PROJECT_ROOT . '/app/Core/Response.php';
require_once PROJECT_ROOT . '/app/Core/DB.php';
require_once PROJECT_ROOT . '/app/Core/Model.php';
require_once PROJECT_ROOT . '/app/Core/QueryBuilder.php';
require_once PROJECT_ROOT . '/app/Core/Auth.php';
require_once PROJECT_ROOT . '/app/Core/ErrorHandler.php';
require_once PROJECT_ROOT . '/app/Core/helpers.php';

// Exceptions
require_once PROJECT_ROOT . '/app/Exceptions/ModelNotFoundException.php';

// Middleware
require_once PROJECT_ROOT . '/app/Http/Middlewares/Middleware.php';
require_once PROJECT_ROOT . '/app/Http/Middlewares/MiddlewareHandler.php';
require_once PROJECT_ROOT . '/app/Http/Middlewares/MiddlewareRegistry.php';

// Models
require_once PROJECT_ROOT . '/app/Models/User.php';
require_once PROJECT_ROOT . '/app/Models/Client.php';
require_once PROJECT_ROOT . '/app/Models/Producte.php';
require_once PROJECT_ROOT . '/app/Models/Comanda.php';
require_once PROJECT_ROOT . '/app/Models/Servei.php';

// Load middleware bootstrap
require_once PROJECT_ROOT . '/app/bootstrap/middleware.php';
