<?php 
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use App\Core\QueryBuilder;

class Comanda extends Model
{
    protected static string $table = 'comandes';
    protected static array $fillable = ['client_id', 'data_comanda', 'total', 'estat', 'direccio_enviament'];
    protected static array $relations = ['client'];

    /** @override */
    public function insert(): void
    {
        if (!isset($this->data_comanda)) {
            $this->data_comanda = date('Y-m-d H:i:s');
        }
        
        $sql = "INSERT INTO " . self::$table 
            . " (client_id, data_comanda, total, estat, direccio_enviament)"
            . " VALUES (?, ?, ?, ?, ?)";
        $params = [
            $this->client_id, 
            $this->data_comanda, 
            $this->total, 
            $this->estat ?? 'Pendent', 
            $this->direccio_enviament
        ];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): void
    {
        $sql = "UPDATE " . self::$table 
            . " SET client_id = ?, data_comanda = ?, total = ?, estat = ?, direccio_enviament = ?"
            . " WHERE id = ?";
        $params = [
            $this->client_id, 
            $this->data_comanda, 
            $this->total, 
            $this->estat, 
            $this->direccio_enviament, 
            $this->id
        ];
        DB::update($sql, $params);
    }

    public function client(): ?Client
    {
        if ($this->client_id === null || $this->client_id === 0) {
            return null;
        }
        return Client::find($this->client_id);
    }

    public static function pendents(): QueryBuilder
    {
        return self::where('estat', 'Pendent');
    }

    public static function completades(): QueryBuilder
    {
        return self::where('estat', 'Completat');
    }

    public static function enviades(): QueryBuilder
    {
        return self::where('estat', 'Enviat');
    }

    public static function cancelades(): QueryBuilder
    {
        return self::where('estat', 'Cancel·lat');
    }
}
