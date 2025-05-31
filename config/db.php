<?php

// Cambia a false para usar la base de datos remota
$useLocal = true;

// Carga las credenciales según el entorno
return [
    'host'     => $useLocal ? 'localhost' : 'sql111.infinityfree.com',
    'username' => $useLocal ? 'vboxuser' : 'if0_38694634',
    'password' => $useLocal ? ' ' : 'KiXaOeT1DJ',
    'database' => $useLocal ? 'jardineria' : 'if0_38694634_jardineria',
];
