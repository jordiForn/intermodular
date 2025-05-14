<?php
declare(strict_types=1);

namespace App\Core;

class DB
{
    private static ?\PDO $connection = null;

    public static function connection(): \PDO
    {
        if (self::$connection === null) {
            $dbHost = DB_HOST;
            $dbName = DB_NAME;
            $dbUser = DB_USER;
            $dbPass = DB_PASS;
            $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";

            self::$connection = new \PDO($dsn, $dbUser, $dbPass);
        }
        return self::$connection;
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
}
