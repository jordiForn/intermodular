<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

// Core classes
require_once __DIR__ . '/../app/Core/Session.php';
require_once __DIR__ . '/../app/Core/Request.php';
require_once __DIR__ . '/../app/Core/Response.php';
require_once __DIR__ . '/../app/Core/DB.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Core/QueryBuilder.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Core/ErrorHandler.php';
require_once __DIR__ . '/../app/Core/helpers.php';

// Exceptions
require_once __DIR__ . '/../app/Exceptions/ModelNotFoundException.php';

// Middleware
require_once __DIR__ . '/../app/Http/Middlewares/Middleware.php';
require_once __DIR__ . '/../app/Http/Middlewares/MiddlewareHandler.php';
require_once __DIR__ . '/../app/Http/Middlewares/MiddlewareRegistry.php';

// Models
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Client.php';
require_once __DIR__ . '/../app/Models/Producte.php';
require_once __DIR__ . '/../app/Models/Comanda.php';
require_once __DIR__ . '/../app/Models/Servei.php';

// Load middleware bootstrap
require_once __DIR__ . '/../app/bootstrap/middleware.php';
