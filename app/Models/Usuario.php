<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use app\Core\DB;
use App\Core\QueryBuilder;

class Usuario extends Model
{

    protected static string $table = 'usuarios';
    protected static array $fillable = ['nombre', 'email', 'password', 'role'];
    protected static array $relations = ['votos', 'peliculas'];
    protected static array $pivots = [/*'pivot', */'puntuacion', 'critica'];

    /** @override */
    public function insert(): void
    {
        $sql = "INSERT INTO " . self::$table
            . " (nombre, email, password, role)"
            . " VALUES (?, ?, ?, ?)";
        $params = [$this->nombre, $this->email, $this->password, $this->role];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): void
    {
        $sql = "UPDATE " . self::$table
            . " SET nombre = ?, email = ?, password = ?, role = ?"
            . " WHERE id = ?";
        $params = [$this->nombre, $this->email, $this->password, $this->role, $this->id];
        DB::update($sql, $params);
    }

    public function votos(): QueryBuilder
    {
        return Voto::where('usuario_id', $this->id);
    }

    /*
    public function peliculas(): array
    {
        $peliculas = [];

        foreach ($this->votos as $voto) {
            $pelicula = $voto->pelicula;
            $pelicula->pivot = $voto;
            $peliculas[] = $pelicula;
        }

        return $peliculas;
    }
    */

    public function peliculas(): QueryBuilder
    {
        $sql = "SELECT p.*, v.puntuacion, v.critica
            FROM pelicula p
            LEFT JOIN votos v ON p.id = v.pelicula_id
            WHERE v.usuario_id = :id";
        $params = [':id' => $this->id];

        return new QueryBuilder(Pelicula::class, $sql, $params);
    }
}
