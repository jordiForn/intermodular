<?php 
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;

class ClientDades extends Model {
    protected static string $table = 'client_dades';
    protected static array $fillable = ['nom', 'cognom', 'email', 'tlf', 'rol', 'nom_login'];
    protected static array $relations = ['client'];

    /** @override */
    public function insert(): void
    {
        $sql = "INSERT INTO " . self::$table 
            . " (nom, cognom, email, tlf, rol, nom_login)"
            . " VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$this->nom, $this->cognom, $this->email, $this->tlf, $this->rol, $this->nom_login];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): void
    {
        $sql = "UPDATE " . self::$table 
            . " SET nom = ?, cognom = ?, email = ?, tlf = ?, rol = ?, nom_login = ?"
            . " WHERE id = ?";
        $params = [$this->nom, $this->cognom, $this->email, $this->tlf, $this->rol, $this->nom_login, $this->id];
        DB::update($sql, $params);
    }

    public function client(): ?Client
    {
        return Client::where('nom_login', $this->nom_login)->first();
    }
}
