<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

// Define method mappings (old method name => new method name)
$methodMappings = [
    'showLogin' => 'showLoginForm',
    'showRegister' => 'showRegisterForm'
];

// List of controllers to check
$controllers = [
    'App\\Http\\Controllers\\AuthController'
];

echo "<h1>Controller Method Compatibility Fix</h1>";

// Check if we should apply fixes
$applyFixes = isset($_GET['apply']) && $_GET['apply'] === 'true';

if (!$applyFixes) {
    echo "<p><a href='?apply=true' style='color: red; font-weight: bold;'>Click here to apply fixes</a> (This will modify files!)</p>";
}

foreach ($controllers as $controllerClass) {
    echo "<h2>Checking $controllerClass</h2>";
    
    if (!class_exists($controllerClass)) {
        echo "<p style='color: red;'>Class not found!</p>";
        continue;
    }
    
    $reflection = new ReflectionClass($controllerClass);
    $existingMethods = [];
    
    foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if ($method->class === $controllerClass) {
            $existingMethods[] = $method->name;
        }
    }
    
    echo "<p>Existing methods: " . implode(', ', $existingMethods) . "</p>";
    
    // Check public files for method calls
    $publicDir = __DIR__;
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($publicDir)
    );
    
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $filePath = $file->getPathname();
            $content = file_get_contents($filePath);
            $modified = false;
            
            foreach ($methodMappings as $oldMethod => $newMethod) {
                // Only replace if the new method exists and the old one doesn't
                if (in_array($newMethod, $existingMethods) && !in_array($oldMethod, $existingMethods)) {
                    $pattern = '/->(' . preg_quote($oldMethod, '/') . ')\(/';
                    $replacement = '->$1(';
                    
                    if (preg_match($pattern, $content)) {
                        echo "<p>Found call to $oldMethod() in " . str_replace($publicDir, '', $filePath) . "</p>";
                        
                        if ($applyFixes) {
                            $newContent = preg_replace($pattern, '->' . $newMethod . '(', $content);
                            if ($newContent !== $content) {
                                file_put_contents($filePath, $newContent);
                                echo "<p style='color: green;'>Fixed: Replaced $oldMethod() with $newMethod()</p>";
                                $modified = true;
                            }
                        }
                    }
                }
            }
            
            if ($modified) {
                echo "<p style='color: green;'>File updated: " . str_replace($publicDir, '', $filePath) . "</p>";
            }
        }
    }
}

if ($applyFixes) {
    echo "<p style='color: green;'>Fixes applied!</p>";
} else {
    echo "<p>No changes made. Click the link above to apply fixes.</p>";
}
