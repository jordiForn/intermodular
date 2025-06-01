<?php

// URL base del proyecto (carpeta 'public')
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1'){
    // Entorno local
    define('BASE_URL', 'http://localhost/intermodular/public');
} else {
    // Entorno servidor Debian (producción)
    define('BASE_URL', 'https://192.168.18.83/intermodular/public');
}

// Ruta de inicio para redirecciones o accesos comunes
define('HOME', BASE_URL . '/productes/index.php');

define('DEBUG', true);

define('ENV', 'development');
