<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Layout</title>
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
        header {
            background-color: #4a5568;
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        footer {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Test Application</h1>
        </header>
        
        <main>
            <?php 
            // Output debug information
            echo "<div style='background-color: #e2f0ff; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>";
            echo "<h3>Debug Information</h3>";
            echo "<p><strong>Layout:</strong> " . __FILE__ . "</p>";
            echo "<p><strong>Content Variable:</strong> " . (isset($content) ? "Defined (" . strlen($content) . " bytes)" : "Not defined") . "</p>";
            echo "</div>";
            
            // Include the content if it exists
            if (isset($content)) {
                echo $content;
            } else {
                echo "<div style='color: red;'>Error: Content variable is not defined!</div>";
            }
            ?>
        </main>
        
        <footer>
            &copy; <?= date('Y') ?> Test Application
        </footer>
    </div>
</body>
</html>
