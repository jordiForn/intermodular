<?php 
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;

class Comanda extends Model {
    protected static string $table = 'comandes';
    protected static array $fillable = ['client_id', 'data_comanda', 'direccio_enviament', 'total'];

    /** @override */
    public function insert(): void
    {
        $sql = "INSERT INTO " . self::$table 
            . " (client_id, data_comanda, direccio_enviament, total)"
            . " VALUES (?, ?, ?, ?)";
        $params = [$this->client_id, $this->data_comanda, $this->direccio_enviament, $this->total];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): void
    {
        $sql = "UPDATE " . self::$table 
            . " SET client_id = ?, data_comanda = ?, direccio_enviament = ?, total = ?"
            . " WHERE id = ?";
        $params = [$this->client_id, $this->data_comanda, $this->direccio_enviament, $this->total, $this->id];
        DB::update($sql, $params);
    }
}
