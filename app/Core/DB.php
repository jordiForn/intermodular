<?php
declare(strict_types=1);

namespace App\Core;

class DB
{
    private static ?\PDO $connection = null;
    private static bool $connectionAttempted = false;
    private static ?string $lastError = null;

    public static function connection(): \PDO
    {
        if (self::$connection === null && !self::$connectionAttempted) {
            self::$connectionAttempted = true;
            
            try {
                $dbHost = DB_HOST;
                $dbName = DB_NAME;
                $dbUser = DB_USER;
                $dbPass = DB_PASS;
                $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";

                // Set PDO options for better error handling
                $options = [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$connection = new \PDO($dsn, $dbUser, $dbPass, $options);
                
                // Log successful connection
                if (class_exists('\\App\\Core\\Debug')) {
                    Debug::log("Database connection established successfully to $dbHost");
                }
            } catch (\PDOException $e) {
                self::$lastError = $e->getMessage();
                
                // Log the error
                if (class_exists('\\App\\Core\\Debug')) {
                    Debug::log("Database connection error: " . $e->getMessage());
                }
                
                // If we're using the remote database and it fails, try to fall back to local
                if (!defined('USE_LOCAL_DB') || !USE_LOCAL_DB) {
                    try {
                        if (class_exists('\\App\\Core\\Debug')) {
                            Debug::log("Attempting fallback to local database");
                        }
                        
                        $localDsn = "mysql:host=localhost;dbname=jardineria;charset=utf8mb4";
                        self::$connection = new \PDO($localDsn, 'root', '', $options);
                        
                        if (class_exists('\\App\\Core\\Debug')) {
                            Debug::log("Fallback to local database successful");
                        }
                    } catch (\PDOException $e2) {
                        self::$lastError .= " | Fallback error: " . $e2->getMessage();
                        
                        if (class_exists('\\App\\Core\\Debug')) {
                            Debug::log("Fallback to local database failed: " . $e2->getMessage());
                        }
                        
                        // Re-throw the original exception
                        throw $e;
                    }
                } else {
                    // Re-throw the exception
                    throw $e;
                }
            }
        }
        
        if (self::$connection === null) {
            throw new \PDOException("Failed to establish database connection: " . self::$lastError);
        }
        
        return self::$connection;
    }

    public static function getLastError(): ?string
    {
        return self::$lastError;
    }

    public static function selectAssoc(string $sql, array $params = []): array
    {
        $stmt = self::prepare($sql, $params);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function select(string $modelName, string $sql, array $params = []): array
    {
        $stmt = self::prepare($sql, $params);
        $stmt->execute();

        $models = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $model = new $modelName();
            $model->id = $row['id'];

            foreach ($model::getFillable() as $field) {
                if (isset($row[$field])) {
                    $model->$field = $row[$field];
                }
            }
            foreach ($model::getPivots() as $pivot) {
                if (isset($row[$pivot])) {
                    $model->$pivot = $row[$pivot];
                }
            }
            foreach ($model::getAggregates() as $aggregated) {
                if (isset($row[$aggregated])) {
                    $model->$aggregated = $row[$aggregated];
                }
            }

            $models[] = $model;
        }

        return $models;
    }

    public static function selectOne(string $modelName, string $sql, array $params = []): ?object
    {
        $models = DB::select($modelName, $sql, $params);
        return !empty($models) ? $models[0] : null;
    }

    public static function insert(string $sql, array $params = []): int
    {
        $stmt = self::prepare($sql, $params);
        $stmt->execute();
        return (int) DB::connection()->lastInsertId();
    }

    public static function update(string $sql, array $params = []): int
    {
        $stmt = self::prepare($sql, $params);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public static function delete(string $sql, array $params = []): int
    {
        return self::update($sql, $params);
    }

    private static function prepare(string $sql, array $params = []): \PDOStatement
    {
        if (DEBUG) {
            $message = "<p style='color: red;'>\$sql = $sql<br>\$params = " . print_r($params, true) . "</p>";
            error_log(strip_tags($message));
            echo $message;
        }

        $db = self::connection();
        $stmt = $db->prepare($sql);

        if (!empty($params)) {
            if (array_is_list($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key + 1, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
                }
            } else {
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
                }
            }
        }

        return $stmt;
    }
    
    /**
     * Test the database connection and return status information
     * 
     * @return array Connection status information
     */
    public static function testConnection(): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'host' => DB_HOST,
            'database' => DB_NAME,
            'user' => DB_USER,
        ];
        
        try {
            $connection = self::connection();
            $result['success'] = true;
            $result['message'] = "Successfully connected to database";
            $result['version'] = $connection->getAttribute(\PDO::ATTR_SERVER_VERSION);
            $result['driver'] = $connection->getAttribute(\PDO::ATTR_DRIVER_NAME);
        } catch (\PDOException $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['code'] = $e->getCode();
        }
        
        return $result;
    }
}
