<?php

require_once __DIR__ . '/../bootstrap/bootstrap.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Pass a test message to the view
    view('test', ['message' => 'This is a test message from the test route.']);
} catch (Throwable $e) {
    // Output the error directly for debugging
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
