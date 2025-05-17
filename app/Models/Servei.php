<?php 
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use App\Core\QueryBuilder;

class Servei extends Model
{
    protected static string $table = 'servei';
    protected static array $fillable = ['nom', 'cat', 'preu_base'];
    protected static array $relations = [];

    /** @override */
    public function insert(): bool
    {
        $sql = "INSERT INTO " . self::$table 
            . " (nom, cat, preu_base)"
            . " VALUES (?, ?, ?)";
        $params = [$this->nom, $this->cat, $this->preu_base];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): bool
    {
        $sql = "UPDATE " . self::$table 
            . " SET nom = ?, cat = ?, preu_base = ?"
            . " WHERE id = ?";
        $params = [$this->nom, $this->cat, $this->preu_base, $this->id];
        DB::update($sql, $params);
    }

    public static function getByCategory(string $categoria): array
    {
        return self::where('cat', $categoria)->get();
    }

    public function getDetails(): ?array
    {
        if ($this->cat === 'jardins') {
            $sql = "SELECT * FROM servei_jardins WHERE id_servei = ?";
            return DB::queryOne($sql, [$this->id]);
        } elseif ($this->cat === 'piscines') {
            $sql = "SELECT * FROM servei_piscines WHERE id_servei = ?";
            return DB::queryOne($sql, [$this->id]);
        }
        
        return null;
    }
}
