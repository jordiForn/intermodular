<?php

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define project root
define('PROJECT_ROOT', dirname(__DIR__));

// Define a simple function to load a view file
function load_view($viewPath, $data = []) {
    // Extract data to make variables available in the view
    extract($data);
    
    // Start output buffering
    ob_start();
    
    // Include the view file
    require PROJECT_ROOT . '/resources/views/' . $viewPath . '.php';
    
    // Get the buffered content and clean the buffer
    return ob_get_clean();
}

try {
    // Load the minimal test view
    $content = load_view('minimal-test', [
        'message' => 'This is a test message from minimal-test.php'
    ]);
    
    // Load the test layout and include the content
    require PROJECT_ROOT . '/resources/views/layouts/test.php';
    
} catch (Throwable $e) {
    // Display any errors
    echo "<div style='color: white; background-color: #cc0000; padding: 20px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h2>Error</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "</div>";
}
