<?php

// Define database connection constants
// Remote database (InfinityFree)
define('REMOTE_DB_HOST', 'sql111.infinityfree.com');
define('REMOTE_DB_USER', 'if0_38694634');
define('REMOTE_DB_PASS', 'KiXaOeT1DJ');
define('REMOTE_DB_NAME', 'if0_38694634_jardineria');

// Local database (XAMPP)
define('LOCAL_DB_HOST', 'localhost');
define('LOCAL_DB_USER', 'root');
define('LOCAL_DB_PASS', ''); // Default XAMPP has no password
define('LOCAL_DB_NAME', 'jardineria');

// Set which database to use (remote or local)
define('USE_LOCAL_DB', true); // Set to false to use remote database

// Set the active database credentials based on the configuration
if (USE_LOCAL_DB) {
    define('DB_HOST', LOCAL_DB_HOST);
    define('DB_USER', LOCAL_DB_USER);
    define('DB_PASS', LOCAL_DB_PASS);
    define('DB_NAME', LOCAL_DB_NAME);
} else {
    define('DB_HOST', REMOTE_DB_HOST);
    define('DB_USER', REMOTE_DB_USER);
    define('DB_PASS', REMOTE_DB_PASS);
    define('DB_NAME', REMOTE_DB_NAME);
}
