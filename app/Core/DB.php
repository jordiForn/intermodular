<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class DB
{
    private static ?PDO $connection = null;
    private static array $config = [];
    
    /**
     * Get the database connection
     * 
     * @return PDO The database connection
     */
    public static function connection(): PDO
    {
        if (self::$connection === null) {
            self::$config = require project_path('config/db.php');
            
            $dsn = "mysql:host=" . self::$config['host'] . ";dbname=" . self::$config['database'] . ";charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            try {
                self::$connection = new PDO($dsn, self::$config['username'], self::$config['password'], $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        
        return self::$connection;
    }
    
    /**
     * Begin a transaction
     * 
     * @return bool True on success, false on failure
     */
    public static function beginTransaction(): bool
    {
        return self::connection()->beginTransaction();
    }
    
    /**
     * Commit a transaction
     * 
     * @return bool True on success, false on failure
     */
    public static function commit(): bool
    {
        return self::connection()->commit();
    }
    
    /**
     * Roll back a transaction
     * 
     * @return bool True on success, false on failure
     */
    public static function rollback(): bool
    {
        return self::connection()->rollBack();
    }
    
    /**
     * Check if a transaction is currently active
     * 
     * @return bool True if a transaction is active, false otherwise
     */
    public static function inTransaction(): bool
    {
        return self::connection()->inTransaction();
    }
    
    /**
     * Test the database connection
     * 
     * @return array Connection status
     */
    public static function testConnection(): array
    {
        try {
            self::connection();
            return [
                'success' => true,
                'message' => 'Connection successful'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Execute a select query
     * 
     * @param string $class The class to map results to
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array The query results
     */
    public static function select(string $class, string $query, array $params = []): array
    {
        $stmt = self::prepare($query, $params);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, $class);
    }
    
    /**
     * Execute a select query and return results as associative array
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array The query results as associative array
     */
    public static function selectAssoc(string $query, array $params = []): array
    {
        $stmt = self::prepare($query, $params);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Execute a select query and return a single result
     * 
     * @param string $class The class to map the result to
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return object|null The query result
     */
    public static function selectOne(string $class, string $query, array $params = []): ?object
    {
        $stmt = self::prepare($query, $params);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, $class);
        $result = $stmt->fetch();
        
        return $result === false ? null : $result;
    }
    
    /**
     * Execute an insert query
     * 
     * @param string $table The table to insert into
     * @param array $data The data to insert
     * @return int The last insert ID
     */
    public static function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $stmt = self::prepare($query, array_values($data));
        $stmt->execute();
        
        return (int)self::connection()->lastInsertId();
    }
    
    /**
     * Execute an update query
     * 
     * @param string $table The table to update
     * @param array $data The data to update
     * @param string $where The where clause
     * @param array $params The where clause parameters
     * @return int The number of affected rows
     */
    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        $set = [];
        
        foreach (array_keys($data) as $column) {
            $set[] = "{$column} = ?";
        }
        
        $set = implode(', ', $set);
        
        $query = "UPDATE {$table} SET {$set} WHERE {$where}";
        
        $stmt = self::prepare($query, array_merge(array_values($data), $params));
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    
    /**
     * Execute a delete query
     * 
     * @param string $table The table to delete from
     * @param string $where The where clause
     * @param array $params The where clause parameters
     * @return int The number of affected rows
     */
    public static function delete(string $table, string $where, array $params = []): int
    {
        $query = "DELETE FROM {$table} WHERE {$where}";
        
        $stmt = self::prepare($query, $params);
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    
    /**
     * Prepare a statement
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return PDOStatement The prepared statement
     */
    public static function prepare(string $query, array $params = []): PDOStatement
    {
        $stmt = self::connection()->prepare($query);
        
        // Check if params is an associative array
        $isAssoc = !empty($params) && array_keys($params) !== range(0, count($params) - 1);
        
        if ($isAssoc) {
            // Handle named parameters
            foreach ($params as $key => $param) {
                $type = match (gettype($param)) {
                    'boolean' => PDO::PARAM_BOOL,
                    'integer' => PDO::PARAM_INT,
                    'NULL' => PDO::PARAM_NULL,
                    default => PDO::PARAM_STR,
                };
                
                // Ensure key has a colon prefix for named parameters
                $paramName = (strpos($key, ':') === 0) ? $key : ':' . $key;
                $stmt->bindValue($paramName, $param, $type);
            }
        } else {
            // Handle positional parameters
            foreach ($params as $i => $param) {
                $type = match (gettype($param)) {
                    'boolean' => PDO::PARAM_BOOL,
                    'integer' => PDO::PARAM_INT,
                    'NULL' => PDO::PARAM_NULL,
                    default => PDO::PARAM_STR,
                };
                
                $stmt->bindValue($i + 1, $param, $type);
            }
        }
        
        return $stmt;
    }
    
    /**
     * Execute a raw query
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return PDOStatement The statement
     */
    public static function raw(string $query, array $params = []): PDOStatement
    {
        $stmt = self::prepare($query, $params);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Create the users table if it doesn't exist
     * 
     * @return bool True if the table was created, false otherwise
     */
    public static function createUsersTable(): bool
    {
        $query = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(20) NOT NULL DEFAULT 'user',
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ";
        
        try {
            self::raw($query);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Add user_id column to client table if it doesn't exist
     * 
     * @return bool True if the column was added, false otherwise
     */
    public static function addUserIdToClientTable(): bool
    {
        // Check if the column exists
        $query = "
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'client' AND COLUMN_NAME = 'user_id';
        ";
        
        $stmt = self::prepare($query, [self::$config['database']]);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return true; // Column already exists
        }
        
        // Add the column
        $query = "
            ALTER TABLE client
            ADD COLUMN user_id INT NULL,
            ADD CONSTRAINT fk_client_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
        ";
        
        try {
            self::raw($query);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
