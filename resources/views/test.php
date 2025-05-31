<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test Page</h1>
        <p>If you can see this, the view rendering system is working correctly.</p>
        
        <h2>Debug Information</h2>
        <ul>
            <li><strong>BASE_URL:</strong> <?= defined('BASE_URL') ? BASE_URL : 'Not defined' ?></li>
            <li><strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?></li>
            <li><strong>Script Path:</strong> <?= $_SERVER['SCRIPT_FILENAME'] ?></li>
            <li><strong>PHP Version:</strong> <?= phpversion() ?></li>
        </ul>
        
        <?php if (!empty($message)): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px;">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="test-view">
            <h2>Test View</h2>
            <p><?php echo htmlspecialchars($message ?? 'No message provided'); ?></p>
            <p>This is a simple test view to verify that the view rendering system is working correctly.</p>
            <p>Current time: <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>
