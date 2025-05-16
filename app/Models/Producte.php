<?php 
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use App\Core\QueryBuilder;

class Producte extends Model
{
    protected static string $table = 'productes';
    protected static array $fillable = ['nom', 'descripcio', 'preu', 'estoc', 'categoria', 'imatge', 'detalls'];
    protected static array $relations = [];

    /** @override */
    public function insert(): void
    {
        $sql = "INSERT INTO " . self::$table 
            . " (nom, descripcio, preu, estoc, categoria, imatge, detalls)"
            . " VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $this->nom, 
            $this->descripcio, 
            $this->preu, 
            $this->estoc, 
            $this->categoria, 
            $this->imatge, 
            $this->detalls
        ];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): void
    {
        $sql = "UPDATE " . self::$table 
            . " SET nom = ?, descripcio = ?, preu = ?, estoc = ?, categoria = ?, imatge = ?, detalls = ?"
            . " WHERE id = ?";
        $params = [
            $this->nom, 
            $this->descripcio, 
            $this->preu, 
            $this->estoc, 
            $this->categoria, 
            $this->imatge, 
            $this->detalls, 
            $this->id
        ];
        DB::update($sql, $params);
    }

    public static function withCategoria(string $categoria): QueryBuilder
    {
        return self::where('categoria', $categoria);
    }

    public static function withBaixEstoc(int $limit = 5): QueryBuilder
    {
        return self::where('estoc', '<', $limit);
    }

    public static function getCategories(): array
    {
        $sql = "SELECT DISTINCT categoria FROM " . self::$table . " WHERE categoria IS NOT NULL";
        $result = DB::query($sql);
        
        $categories = [];
        foreach ($result as $row) {
            $categories[] = $row['categoria'];
        }
        
        return $categories;
    }
}
