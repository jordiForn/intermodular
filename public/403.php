<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

// Trigger a 403 error
http_error(403, 'No tienes permiso para acceder a este recurso');
