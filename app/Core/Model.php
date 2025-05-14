<?php
declare(strict_types=1);

namespace App\Core;
use App\Exceptions\ModelNotFoundException;

abstract class Model
{
    protected static string $table;
    protected static array $fillable;
    protected static array $aggregates;
    protected static array $pivots;
    protected static array $relations;
    
    protected ?int $id = null;

    public function __get($propiedad)
    {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        }

        if (method_exists($this, $propiedad) && in_array($propiedad, static::$relations ?? [])) {
            $resultado = $this->$propiedad();

            if ($resultado instanceof QueryBuilder) {
                return $this->$propiedad = $resultado->get();
            }
            return $this->$propiedad = $resultado;
        }

        return null;
    }

    public function __set($propiedad, $valor)
    {
        if ($propiedad === 'id') {
            $this->$propiedad = (int) $valor;
        } elseif (
            in_array($propiedad, static::$fillable ?? []) ||
            in_array($propiedad, static::$relations ?? []) ||
            in_array($propiedad, static::$aggregates ?? []) ||
            in_array($propiedad, static::$pivots ?? [])
        ) {
            $this->$propiedad = $valor;

        } else {
            throw new \RuntimeException("La propiedad '$propiedad' no está permitida en el modelo.");
        }
    }

    public static function getTable(){
        return static::$table ?? "";
    }

    public static function getFillable(){
        return static::$fillable ?? [];
    }

    public static function getAggregates(){
        return static::$aggregates ?? [];
    }

    public static function getPivots(){
        return static::$pivots ?? [];
    }

    public static function where(string $column, mixed $operator = "=", mixed $value = null): QueryBuilder
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = "=";
        }
        $qb = new QueryBuilder(static::class);
        return $qb->where($column, $operator, $value);
    }

    public static function orderBy(string $column, string $direction = "ASC"): QueryBuilder
    {
        $qb = new QueryBuilder(static::class);
        return $qb->orderBy($column, $direction);
    }

    public static function limit(int $limit): QueryBuilder
    {
        $qb = new QueryBuilder(static::class);
        return $qb->limit($limit);
    }

    public static function offset(int $offset): QueryBuilder
    {
        $qb = new QueryBuilder(static::class);
        return $qb->limit($offset);
    }

    public static function paginate(int $itemsPerPage, int $page): array
    {
        $qb = new QueryBuilder(static::class);
        return $qb->paginate($itemsPerPage, $page);
    }

    public static function count(): int
    {
        $qb = new QueryBuilder(static::class);
        return $qb->count();
    }

    protected abstract function insert(): void;
    protected abstract function update(): void;

    public static function all(): array
    {
        $sql = "SELECT * FROM " . static::$table;
        return DB::select(static::class, $sql);
    }

    public static function find(int $id): ?static
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE id = :id";
        $params = [':id' => $id];
        return DB::selectOne(static::class, $sql, $params);
    }

    public static function findOrFail(int $id): static
    {
        $model = self::find($id);

        if (!$model) {
            throw new ModelNotFoundException("El modelo con ID($id) no fue encontrado");
        }
        return $model;
    }

    public function save(): void
    {
        if ($this->id === null) {
            $this->insert();
        } else {
            $this->update();
        }
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM " . static::$table . " WHERE id = :id";
        $params = ['id' => $this->id];
        return DB::delete($sql, $params) === 1;
    }
}
