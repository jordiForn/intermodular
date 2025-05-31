<?php

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Output directly without using the view system
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Direct Output Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
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
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Direct Output Test</h1>
        
        <div class='success'>
            <p><strong>Success!</strong> If you can see this message, the web server is correctly processing PHP files and outputting content directly.</p>
        </div>
        
        <div class='info'>
            <h2>Server Information</h2>
            <ul>
                <li><strong>PHP Version:</strong> " . phpversion() . "</li>
                <li><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</li>
                <li><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</li>
                <li><strong>Script Filename:</strong> " . ($_SERVER['SCRIPT_FILENAME'] ?? 'Unknown') . "</li>
                <li><strong>Request URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "</li>
                <li><strong>Current Time:</strong> " . date('Y-m-d H:i:s') . "</li>
            </ul>
        </div>
        
        <p>This test bypasses the application's view rendering system and outputs HTML directly. If this page displays correctly but other pages don't, the issue is likely in the view rendering system.</p>
        
        <h2>Next Steps</h2>
        <ul>
            <li><a href='minimal-test.php'>Try the Minimal Test</a> - A simplified view rendering test</li>
            <li><a href='view-test.php'>Try the View Test</a> - A comprehensive view rendering test</li>
            <li><a href='check-filesystem.php'>Check File System</a> - Verify file permissions and structure</li>
            <li><a href='phpinfo.php'>PHP Info</a> - Check PHP configuration</li>
        </ul>
    </div>
</body>
</html>";
