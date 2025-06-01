<?php
declare(strict_types=1);

namespace App\Core;

class Model
{
    protected static string $table;
    protected static array $fillable;
    protected static array $aggregates;
    protected static array $pivots;
    protected static array $relations;
    
    protected ?int $id = null;
    
    // Dynamic properties container to avoid deprecation warnings
    protected array $attributes = [];

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        
        if (array_key_exists($property, $this->attributes)) {
            return $this->attributes[$property];
        }

        if (method_exists($this, $property) && in_array($property, static::$relations ?? [])) {
            $result = $this->$property();

            if ($result instanceof QueryBuilder) {
                return $this->attributes[$property] = $result->get();
            }
            return $this->attributes[$property] = $result;
        }

        return null;
    }

    public function __set($property, $value)
{
    // Asegura que $fillable es un array
    $fillable = static::$fillable ?? [];
    $relations = static::$relations ?? [];
    $aggregates = static::$aggregates ?? [];
    $pivots = static::$pivots ?? [];

    if ($property === 'id') {
        $this->$property = (int) $value;
    } elseif (
        in_array($property, $fillable) ||
        in_array($property, $relations) ||
        in_array($property, $aggregates) ||
        in_array($property, $pivots)
    ) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        } else {
            $this->attributes[$property] = $value;
        }
    } else {
        throw new \RuntimeException("La propiedad '$property' no está permitida en el modelo.");
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

    protected function insert(): bool
    {
        // This method should be implemented by child classes
        throw new \RuntimeException("El método insert() debe ser implementado por la clase hija.");
    }
    
    protected function update(): bool
    {
        // This method should be implemented by child classes
        throw new \RuntimeException("El método update() debe ser implementado por la clase hija.");
    }

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
            throw new \RuntimeException("El modelo con ID($id) no fue encontrado");
        }
        return $model;
    }

    public function save(): bool
{
    if ($this->id === null) {
        return $this->insert();
    } else {
        return $this->update();
    }
}

    public function delete(): bool
    {
    $where = "id = :id";
    $params = ['id' => $this->id];
    return DB::delete(static::$table, $where, $params) === 1;
}
    
    /**
     * Convert the model to an array
     * 
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        
        // Add the ID
        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        
        // Add all attributes
        foreach ($this->attributes as $key => $value) {
            $data[$key] = $value;
        }
        
        return $data;
    }
}
