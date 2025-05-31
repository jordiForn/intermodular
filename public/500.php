<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

// Trigger a 500 error
http_error(500, 'Ha ocurrido un error interno en el servidor');
