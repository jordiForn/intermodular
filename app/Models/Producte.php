<?php 
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;

class Producte extends Model {
    protected static string $table = 'productes';
    protected static array $fillable = ['nom', 'descripcio', 'preu', 'estoc', 'categoria', 'imatge'];

    /** @override */
    public function insert(): void
    {
        $sql = "INSERT INTO " . self::$table 
            . " (nom, descripcio, preu, estoc, categoria, imatge)"
            . " VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$this->nom, $this->descripcio, $this->preu, $this->estoc, $this->categoria, $this->imatge];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): void
    {
        $sql = "UPDATE " . self::$table 
            . " SET nom = ?, descripcio = ?, preu = ?, estoc = ?, categoria = ?, imatge = ?"
            . " WHERE id = ?";
        $params = [$this->nom, $this->descripcio, $this->preu, $this->estoc, $this->categoria, $this->imatge, $this->id];
        DB::update($sql, $params);
    }

    public static function getCategories(): array
    {
        $sql = "SELECT DISTINCT categoria FROM " . self::$table;
        $result = DB::selectAssoc($sql);
        
        $categories = [];
        foreach ($result as $row) {
            $categories[] = $row['categoria'];
        }
        
        return $categories;
    }

    public static function getByCategory(string $category): array
    {
        return self::where('categoria', $category)->get();
    }

    public static function getAvailable(): array
    {
        $sql = "SELECT * FROM " . self::$table . " 
                WHERE estoc > 0
                ORDER BY 
                    CASE 
                        WHEN categoria = 'Plantes i llavors' THEN 1
                        WHEN categoria = 'Terra i adobs' THEN 2
                        WHEN categoria = 'Ferramentes' THEN 3
                        ELSE 4
                    END,
                    nom";
        
        return DB::select(self::class, $sql);
    }
}
