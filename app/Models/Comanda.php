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
    public function insert(): bool
{
    if (!isset($this->data_comanda)) {
        $this->data_comanda = date('Y-m-d H:i:s');
    }

    $data = [
        'client_id' => $this->client_id,
        'data_comanda' => $this->data_comanda,
        'total' => $this->total,
        'estat' => $this->estat ?? 'Pendent',
        'direccio_enviament' => $this->direccio_enviament
    ];
    $this->id = DB::insert(self::$table, $data);
    return $this->id !== false;
}

    /** @override */
    public function update(): bool
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
        return self::whereRaw('LOWER(estat) = ?', ['pendent']);
    }

    public static function completades(): QueryBuilder
    {
        return self::whereRaw('LOWER(estat) = ?', ['completat']);
    }

    public static function enviades(): QueryBuilder
    {
        return self::whereRaw('LOWER(estat) = ?', ['enviat']);
    }

    public static function cancelades(): QueryBuilder
    {
        return self::whereRaw('LOWER(estat) = ?', ['cancel·lat']);
    }
}
