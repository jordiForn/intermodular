<?php 
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;

class Client extends Model {
    protected static string $table = 'client';
    protected static array $fillable = ['nom', 'cognom', 'email', 'tlf', 'nom_login', 'contrasena', 'rol', 'missatge', 'consulta'];

    /** @override */
    public function insert(): void
    {
        $sql = "INSERT INTO " . self::$table 
            . " (nom, cognom, email, tlf, nom_login, contrasena, rol, missatge, consulta)"
            . " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$this->nom, $this->cognom, $this->email, $this->tlf, $this->nom_login, $this->contrasena, $this->rol, $this->missatge, $this->consulta];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): void
    {
        $sql = "UPDATE " . self::$table 
            . " SET nom = ?, cognom = ?, email = ?, tlf = ?, nom_login = ?, contrasena = ?, rol = ?, missatge = ?, consulta = ?"
            . " WHERE id = ?";
        $params = [$this->nom, $this->cognom, $this->email, $this->tlf, $this->nom_login, $this->contrasena, $this->rol, $this->missatge, $this->consulta, $this->id];
        DB::update($sql, $params);
    }

    public static function findByUsername(string $username): ?Client
    {
        return self::where('nom_login', $username)->first();
    }
}
