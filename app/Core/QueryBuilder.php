<?php
declare(strict_types=1);

namespace App\Core;

class QueryBuilder {

    private string $modelName;
    private string $table;
    private string $whereClause = "";
    private string $orderByClause = "";
    private int $limit = 0;
    private int $offset = 0;

    private ?string $sql;
    private array $params;

    private bool $namedPlaceholders = true;

    public function __construct(string $modelName, ?string $sql = null, array $params = []) {
        $this->modelName = $modelName;
        $this->table = $modelName::getTable();
        $this->sql = $sql;
        $this->params = $params;

        // Determina si usar placeholders nombrados o posicionales
        $this->namedPlaceholders = empty($params) || !array_is_list($params);
    }

    public function where(string $column, string $operator = "=", mixed $value = null): self {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = "=";
        }

        $prefix = ($this->whereClause || str_contains(strtoupper($this->sql ?? ''), "WHERE")) ? " AND" : " WHERE";

        if ($value === null) {
            $operator = $operator === "=" ? "IS" : "IS NOT";
            $this->whereClause .= "$prefix $column $operator NULL";
        } else if($this->namedPlaceholders){
            $this->whereClause .= "$prefix $column $operator :$column";
            $this->params[":$column"] = $value;
        } else {
            $this->whereClause .= "$prefix $column $operator ?";
            $this->params[] = $value;
        }

        return $this;
    }

    public function orderBy(string $column, string $direction = "ASC"): self {
        $allowedDirections = ["ASC", "DESC"];
        if(!in_array(strtoupper($direction), $allowedDirections)) {
            throw new \InvalidArgumentException("La ordenación sólo puede ser ascendente (ASC) o descendente (DESC)");
        }

        $this->orderByClause = " ORDER BY $column $direction";
        return $this;
    }

    public function limit(int $limit): self {
        $this->limit = $limit;
        return $this;
    }
    
    public function offset(int $offset): self {
        $this->offset = $offset;
        return $this;
    }

    private function build(): void
    {
        if(!$this->sql)
            $this->sql = "SELECT * FROM {$this->table}";

        if ($this->whereClause) {
            $this->sql .= $this->whereClause;
        }
            
        if ($this->orderByClause) {
            $this->sql .= $this->orderByClause;
        }
            
        if ($this->limit > 0) {
            if($this->namedPlaceholders){
                $this->sql .= " LIMIT :limit";
                $this->params[':limit'] = $this->limit;
            } else {
                $this->sql .= " LIMIT ?";
                $this->params[] = $this->limit;
            }
        }
        
        if ($this->offset > 0) {
            if($this->namedPlaceholders){
                $this->sql .= " OFFSET :offset";
                $this->params[':offset'] = $this->offset;
            } else {
                $this->sql .= " OFFSET ?";
                $this->params[] = $this->offset;
            }
        }
    }

    public function get(): array {
        $this->build();
        return DB::select($this->modelName, $this->sql, $this->params);
    }

    public function first() {
        $this->limit = 1;
        $this->build();
        return DB::selectOne($this->modelName, $this->sql, $this->params);
    }

    public function paginate(int $itemsPerPage, int $page): array {
        $this->limit = $itemsPerPage;
        $this->offset = ($page - 1) * $itemsPerPage;
        $this->build();
        return DB::select($this->modelName, $this->sql, $this->params);
    }

    public function count(): int {
        $this->sql = "SELECT COUNT(*) AS total FROM " . $this->table;
        $this->build();
        return DB::selectAssoc($this->sql, $this->params)[0]['total'];
    }
}
