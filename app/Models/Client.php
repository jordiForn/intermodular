<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use App\Core\QueryBuilder;

class Client extends Model
{
    protected static string $table = 'client';
    protected static array $fillable = ['user_id', 'nom', 'cognom', 'tlf', 'direccio', 'ciutat', 'codi_postal', 'provincia', 'pais', 'consulta', 'missatge', 'created_at', 'updated_at'];
    protected static array $relations = ['user', 'comandes'];

    /** @override */
    public function insert(): void
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO " . self::$table 
            . " (user_id, nom, cognom, tlf, direccio, ciutat, codi_postal, provincia, pais, consulta, missatge, created_at, updated_at)"
            . " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $this->user_id,
            $this->nom,
            $this->cognom,
            $this->tlf,
            $this->direccio ?? null,
            $this->ciutat ?? null,
            $this->codi_postal ?? null,
            $this->provincia ?? null,
            $this->pais ?? null,
            $this->consulta ?? null,
            $this->missatge ?? null,
            $this->created_at,
            $this->updated_at
        ];
        $this->id = DB::insert($sql, $params);
    }

    /** @override */
    public function update(): void
    {
        $this->updated_at = date('Y-m-d H:i:s');
        
        $sql = "UPDATE " . self::$table 
            . " SET user_id = ?, nom = ?, cognom = ?, tlf = ?, direccio = ?, ciutat = ?, codi_postal = ?, provincia = ?, pais = ?, consulta = ?, missatge = ?, updated_at = ?"
            . " WHERE id = ?";
        $params = [
            $this->user_id,
            $this->nom,
            $this->cognom,
            $this->tlf,
            $this->direccio ?? null,
            $this->ciutat ?? null,
            $this->codi_postal ?? null,
            $this->provincia ?? null,
            $this->pais ?? null,
            $this->consulta ?? null,
            $this->missatge ?? null,
            $this->updated_at,
            $this->id
        ];
        DB::update($sql, $params);
    }

    public function user(): ?User
    {
        return User::find($this->user_id);
    }

    public function comandes(): QueryBuilder
    {
        return Comanda::where('client_id', $this->id);
    }

    // Helper method to get full name
    public function getNomComplet(): string
    {
        return $this->nom . ' ' . $this->cognom;
    }

    // Helper method to get full address
    public function getAdrecaCompleta(): ?string
    {
        if (empty($this->direccio)) {
            return null;
        }
        
        $parts = [$this->direccio];
        
        if (!empty($this->codi_postal) || !empty($this->ciutat)) {
            $parts[] = trim($this->codi_postal . ' ' . $this->ciutat);
        }
        
        if (!empty($this->provincia)) {
            $parts[] = $this->provincia;
        }
        
        if (!empty($this->pais)) {
            $parts[] = $this->pais;
        }
        
        return implode(', ', $parts);
    }

    public static function findByNomLogin(string $nomLogin): ?self
    {
        return self::where('nom_login', $nomLogin)->first();
    }
}
