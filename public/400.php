<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

// Trigger a 400 error
http_error(400, 'La solicitud contiene errores o datos incorrectos');
