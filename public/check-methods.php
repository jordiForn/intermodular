<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

// List of controllers to check
$controllers = [
    'App\\Http\\Controllers\\AuthController',
    'App\\Http\\Controllers\\ProducteController',
    'App\\Http\\Controllers\\ClientController',
    'App\\Http\\Controllers\\ComandaController',
    'App\\Http\\Controllers\\ServeiController'
];

echo "<h1>Controller Method Compatibility Check</h1>";

foreach ($controllers as $controllerClass) {
    echo "<h2>Checking $controllerClass</h2>";
    
    if (!class_exists($controllerClass)) {
        echo "<p style='color: red;'>Class not found!</p>";
        continue;
    }
    
    $reflection = new ReflectionClass($controllerClass);
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    
    echo "<ul>";
    foreach ($methods as $method) {
        if ($method->class === $controllerClass) {
            echo "<li>{$method->name}()</li>";
        }
    }
    echo "</ul>";
}

// Check for method calls in public files
echo "<h2>Checking for method calls in public files</h2>";

$publicDir = __DIR__;
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($publicDir)
);

$methodCalls = [];

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        // Simple regex to find controller method calls
        if (preg_match_all('/->([a-zA-Z0-9_]+)\(/', $content, $matches)) {
            foreach ($matches[1] as $methodName) {
                $relativePath = str_replace($publicDir, '', $file->getPathname());
                $methodCalls[$methodName][] = $relativePath;
            }
        }
    }
}

echo "<ul>";
foreach ($methodCalls as $methodName => $files) {
    echo "<li><strong>$methodName()</strong> called in: ";
    echo implode(', ', $files);
    echo "</li>";
}
echo "</ul>";
