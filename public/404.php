<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

// Trigger a 404 error
http_error(404, 'La página solicitada no existe');
