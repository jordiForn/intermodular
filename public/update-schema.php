<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

use App\Core\DB;

// Check if user is admin
if (!Auth::check() || Auth::user()->role !== 'admin') {
    http_error(403, 'No tens permís per accedir a aquesta pàgina.');
    exit;
}

// Display header
echo "<h1>Database Schema Update</h1>";

try {
    // Check if clients table exists
    $checkClientsTable = "SHOW TABLES LIKE 'clients'";
    $clientsTableExists = !empty(DB::selectAssoc($checkClientsTable));
    
    // Check if client table exists
    $checkClientTable = "SHOW TABLES LIKE 'client'";
    $clientTableExists = !empty(DB::selectAssoc($checkClientTable));
    
    echo "<h2>Current Schema Status:</h2>";
    echo "<ul>";
    echo "<li>'clients' table exists: " . ($clientsTableExists ? 'Yes' : 'No') . "</li>";
    echo "<li>'client' table exists: " . ($clientTableExists ? 'Yes' : 'No') . "</li>";
    echo "</ul>";
    
    // If clients table exists but client table doesn't, rename it
    if ($clientsTableExists && !$clientTableExists) {
        echo "<h2>Updating Schema:</h2>";
        echo "<p>Renaming 'clients' table to 'client'...</p>";
        
        $renameTable = "RENAME TABLE clients TO client";
        DB::update($renameTable);
        
        echo "<p>Table renamed successfully!</p>";
    } 
    // If both tables exist, we need to merge them
    elseif ($clientsTableExists && $clientTableExists) {
        echo "<h2>Warning:</h2>";
        echo "<p>Both 'clients' and 'client' tables exist. Manual intervention required.</p>";
        echo "<p>Please backup your data and contact the system administrator.</p>";
    }
    // If only client table exists, we're good
    elseif (!$clientsTableExists && $clientTableExists) {
        echo "<h2>Schema Status:</h2>";
        echo "<p>Schema is already correct. No changes needed.</p>";
    }
    // If neither table exists, create the client table
    else {
        echo "<h2>Warning:</h2>";
        echo "<p>Neither 'clients' nor 'client' table exists. Creating 'client' table...</p>";
        
        $createClientTable = "
        CREATE TABLE `client` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) DEFAULT NULL,
          `id_referit` int(11) DEFAULT NULL,
          `id_referidor` int(11) DEFAULT NULL,
          `id_fidelitat` int(11) DEFAULT NULL,
          `nom` varchar(100) DEFAULT NULL,
          `cognom` varchar(100) DEFAULT NULL,
          `email` varchar(100) DEFAULT NULL,
          `tlf` varchar(15) DEFAULT NULL,
          `consulta` text DEFAULT NULL,
          `missatge` text DEFAULT NULL,
          `nom_login` varchar(50) DEFAULT NULL,
          `contrasena` varchar(255) DEFAULT NULL,
          `rol` tinyint(1) NOT NULL DEFAULT 0,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        
        DB::update($createClientTable);
        
        echo "<p>Table created successfully!</p>";
    }
    
    // Check foreign key constraints
    $checkForeignKeys = "
    SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
    AND (REFERENCED_TABLE_NAME = 'clients' OR REFERENCED_TABLE_NAME = 'client')
    ";
    
    $foreignKeys = DB::selectAssoc($checkForeignKeys);
    
    if (!empty($foreignKeys)) {
        echo "<h2>Foreign Key Constraints:</h2>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Table</th><th>Column</th><th>Constraint</th><th>Referenced Table</th><th>Referenced Column</th></tr>";
        
        foreach ($foreignKeys as $fk) {
            echo "<tr>";
            echo "<td>" . $fk['TABLE_NAME'] . "</td>";
            echo "<td>" . $fk['COLUMN_NAME'] . "</td>";
            echo "<td>" . $fk['CONSTRAINT_NAME'] . "</td>";
            echo "<td>" . $fk['REFERENCED_TABLE_NAME'] . "</td>";
            echo "<td>" . $fk['REFERENCED_COLUMN_NAME'] . "</td>";
            echo "</tr>";
            
            // If the foreign key references 'clients', update it to 'client'
            if ($fk['REFERENCED_TABLE_NAME'] === 'clients') {
                $constraintName = $fk['CONSTRAINT_NAME'];
                $tableName = $fk['TABLE_NAME'];
                $columnName = $fk['COLUMN_NAME'];
                $referencedColumn = $fk['REFERENCED_COLUMN_NAME'];
                
                echo "<tr><td colspan='5'>";
                echo "Updating foreign key constraint $constraintName...";
                
                // Drop the existing foreign key
                $dropFk = "ALTER TABLE `$tableName` DROP FOREIGN KEY `$constraintName`";
                DB::update($dropFk);
                
                // Add the new foreign key
                $addFk = "ALTER TABLE `$tableName` ADD CONSTRAINT `{$constraintName}_new` FOREIGN KEY (`$columnName`) REFERENCES `client` (`$referencedColumn`)";
                DB::update($addFk);
                
                echo "Done!";
                echo "</td></tr>";
            }
        }
        
        echo "</table>";
    } else {
        echo "<h2>Foreign Key Constraints:</h2>";
        echo "<p>No foreign key constraints found referencing 'clients' or 'client' tables.</p>";
    }
    
    echo "<h2>Schema Update Complete</h2>";
    echo "<p><a href='/'>Return to Home Page</a></p>";
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
