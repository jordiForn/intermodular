<?php 
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;

class Servei extends Model {
    protected static string $table = 'servei';
    protected static array $fillable = ['nom', 'preu_base', 'cat'];

    /** @override */
    public function insert(): void
    {
        $sql = "INSERT INTO " . self::$table 
            . " (nom, preu_base, cat)"
            . " VALUES (?, ?, ?)";
        $params = [$this->nom, $this->preu_base, $this->cat];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): void
    {
        $sql = "UPDATE " . self::$table 
            . " SET nom = ?, preu_base = ?, cat = ?"
            . " WHERE id = ?";
        $params = [$this->nom, $this->preu_base, $this->cat, $this->id];
        DB::update($sql, $params);
    }

    public static function getByCategory(string $category): array
    {
        return self::where('cat', $category)->get();
    }
}
