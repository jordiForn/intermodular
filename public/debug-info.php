<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Function to safely check if a directory exists and is writable
function check_dir($path) {
    $exists = is_dir($path);
    $writable = $exists && is_writable($path);
    return [
        'path' => $path,
        'exists' => $exists,
        'writable' => $writable
    ];
}

// Function to safely check if a file exists and is readable
function check_file($path) {
    $exists = file_exists($path);
    $readable = $exists && is_readable($path);
    return [
        'path' => $path,
        'exists' => $exists,
        'readable' => $readable,
        'size' => $exists ? filesize($path) : 0
    ];
}

// Get the project root directory
$projectRoot = dirname(__DIR__);

// Check important directories
$dirs = [
    'Project Root' => check_dir($projectRoot),
    'Logs Directory' => check_dir($projectRoot . '/logs'),
    'App Directory' => check_dir($projectRoot . '/app'),
    'Core Directory' => check_dir($projectRoot . '/app/Core'),
    'Views Directory' => check_dir($projectRoot . '/resources/views'),
    'Layouts Directory' => check_dir($projectRoot . '/resources/views/layouts'),
    'Public Directory' => check_dir($projectRoot . '/public'),
    'Bootstrap Directory' => check_dir($projectRoot . '/bootstrap'),
    'Config Directory' => check_dir($projectRoot . '/config')
];

// Check important files
$files = [
    'Debug Class' => check_file($projectRoot . '/app/Core/Debug.php'),
    'Helpers File' => check_file($projectRoot . '/app/Core/helpers.php'),
    'Bootstrap File' => check_file($projectRoot . '/bootstrap/bootstrap.php'),
    'Config File' => check_file($projectRoot . '/config/config.php'),
    'DB Config File' => check_file($projectRoot . '/config/db.php'),
    'Main Layout' => check_file($projectRoot . '/resources/views/layouts/app.php'),
    'Test View' => check_file($projectRoot . '/resources/views/test.php')
];

// Check PHP configuration
$phpConfig = [
    'PHP Version' => phpversion(),
    'Display Errors' => ini_get('display_errors'),
    'Error Reporting' => ini_get('error_reporting'),
    'Memory Limit' => ini_get('memory_limit'),
    'Max Execution Time' => ini_get('max_execution_time'),
    'Upload Max Filesize' => ini_get('upload_max_filesize'),
    'Post Max Size' => ini_get('post_max_size'),
    'Open Basedir' => ini_get('open_basedir'),
    'Temporary Directory' => sys_get_temp_dir(),
    'Current Working Directory' => getcwd()
];

// Check server information
$serverInfo = [
    'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'Server Name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
    'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
    'Script Filename' => $_SERVER['SCRIPT_FILENAME'] ?? 'Unknown',
    'Request URI' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
    'Server Protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
    'Request Method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
    'Remote Addr' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
    'HTTP User Agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
];

// Output the debug information
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1, h2 {
            color: #0066cc;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .warning {
            color: orange;
        }
    </style>
</head>
<body>
    <h1>Debug Information</h1>
    
    <h2>Directory Checks</h2>
    <table>
        <tr>
            <th>Directory</th>
            <th>Path</th>
            <th>Exists</th>
            <th>Writable</th>
        </tr>
        <?php foreach ($dirs as $name => $dir): ?>
        <tr>
            <td><?= htmlspecialchars($name) ?></td>
            <td><?= htmlspecialchars($dir['path']) ?></td>
            <td class="<?= $dir['exists'] ? 'success' : 'error' ?>">
                <?= $dir['exists'] ? 'Yes' : 'No' ?>
            </td>
            <td class="<?= $dir['writable'] ? 'success' : ($dir['exists'] ? 'warning' : 'error') ?>">
                <?= $dir['writable'] ? 'Yes' : 'No' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>File Checks</h2>
    <table>
        <tr>
            <th>File</th>
            <th>Path</th>
            <th>Exists</th>
            <th>Readable</th>
            <th>Size</th>
        </tr>
        <?php foreach ($files as $name => $file): ?>
        <tr>
            <td><?= htmlspecialchars($name) ?></td>
            <td><?= htmlspecialchars($file['path']) ?></td>
            <td class="<?= $file['exists'] ? 'success' : 'error' ?>">
                <?= $file['exists'] ? 'Yes' : 'No' ?>
            </td>
            <td class="<?= $file['readable'] ? 'success' : ($file['exists'] ? 'warning' : 'error') ?>">
                <?= $file['readable'] ? 'Yes' : 'No' ?>
            </td>
            <td><?= $file['size'] ?> bytes</td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>PHP Configuration</h2>
    <table>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
        <?php foreach ($phpConfig as $name => $value): ?>
        <tr>
            <td><?= htmlspecialchars($name) ?></td>
            <td><?= htmlspecialchars($value) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>Server Information</h2>
    <table>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
        <?php foreach ($serverInfo as $name => $value): ?>
        <tr>
            <td><?= htmlspecialchars($name) ?></td>
            <td><?= htmlspecialchars($value) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>Test Debug Log</h2>
    <?php
    // Try to create a test log entry
    $logDir = $projectRoot . '/logs';
    $testLogFile = $logDir . '/test-debug.log';
    $logCreated = false;
    $logMessage = "Test log entry created at " . date('Y-m-d H:i:s');
    
    if (is_dir($logDir) && is_writable($logDir)) {
        try {
            file_put_contents($testLogFile, $logMessage . "\n");
            $logCreated = true;
        } catch (\Throwable $e) {
            $logError = $e->getMessage();
        }
    }
    ?>
    
    <div class="<?= $logCreated ? 'success' : 'error' ?>">
        <?php if ($logCreated): ?>
            <p>Successfully created test log file: <?= htmlspecialchars($testLogFile) ?></p>
            <p>Log message: <?= htmlspecialchars($logMessage) ?></p>
        <?php else: ?>
            <p>Failed to create test log file: <?= htmlspecialchars($testLogFile) ?></p>
            <?php if (isset($logError)): ?>
                <p>Error: <?= htmlspecialchars($logError) ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <h2>Project Path Function Test</h2>
    <?php
    // Try to load the project_path function
    $projectPathAvailable = false;
    $projectPathResult = null;
    $projectPathError = null;
    
    if (file_exists($projectRoot . '/app/Core/helpers.php')) {
        try {
            require_once $projectRoot . '/app/Core/helpers.php';
            if (function_exists('project_path')) {
                $projectPathAvailable = true;
                $projectPathResult = project_path('logs/debug.log');
            }
        } catch (\Throwable $e) {
            $projectPathError = $e->getMessage();
        }
    }
    ?>
    
    <div class="<?= $projectPathAvailable ? 'success' : 'error' ?>">
        <?php if ($projectPathAvailable): ?>
            <p>project_path() function is available</p>
            <p>Test result: <?= htmlspecialchars($projectPathResult) ?></p>
        <?php else: ?>
            <p>project_path() function is not available</p>
            <?php if ($projectPathError): ?>
                <p>Error: <?= htmlspecialchars($projectPathError) ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
