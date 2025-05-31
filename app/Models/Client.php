<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use App\Core\QueryBuilder;

class Client extends Model
{
    protected static string $table = 'client';
    protected static array $fillable = [
        'user_id', 'nom', 'cognom', 'email', 'tlf', 'consulta', 
        'missatge', 'nom_login', 'contrasena', 'rol', 'id_referit', 'id_referidor', 'id_fidelitat'
    ];
    
    
    public ?int $id = null;
    public ?int $user_id = null;
    public ?string $nom = null;
    public ?string $cognom = null;
    public ?string $email = null;
    public ?string $tlf = null;
    public ?string $consulta = null;
    public ?string $missatge = null;
    public ?string $nom_login = null;
    public ?string $contrasena = null;
    public int $rol = 0;
    public ?int $id_referit = null;
    public ?int $id_referidor = null;
    public ?int $id_fidelitat = null;
    public $orderCount = 0;
    /**
     * Insert a new client record
     */
    public function insert(): bool
    {
        $data = [
            'user_id' => $this->user_id,
            'nom' => $this->nom,
            'cognom' => $this->cognom,
            'email' => $this->email,
            'tlf' => $this->tlf,
            'nom_login' => $this->nom_login,
            'contrasena' => $this->contrasena,
            'rol' => $this->rol
        ];
        
        // Add optional fields if they exist
        if ($this->consulta !== null) {
            $data['consulta'] = $this->consulta;
        }
        
        if ($this->missatge !== null) {
            $data['missatge'] = $this->missatge;
        }
        
        if ($this->id_referit !== null) {
            $data['id_referit'] = $this->id_referit;
        }
        
        if ($this->id_referidor !== null) {
            $data['id_referidor'] = $this->id_referidor;
        }
        
        if ($this->id_fidelitat !== null) {
            $data['id_fidelitat'] = $this->id_fidelitat;
        }
        
        $this->id = DB::insert(static::$table, $data);
        return $this->id !== null && $this->id > 0;
    }
    
    /**
     * Update an existing client record
     */
    public function update(): bool
    {
        $data = [
            'user_id' => $this->user_id,
            'nom' => $this->nom,
            'cognom' => $this->cognom,
            'email' => $this->email,
            'tlf' => $this->tlf,
            'nom_login' => $this->nom_login,
            'contrasena' => $this->contrasena,
            'rol' => $this->rol
        ];
        
        // Add optional fields if they exist
        if ($this->consulta !== null) {
            $data['consulta'] = $this->consulta;
        }
        
        if ($this->missatge !== null) {
            $data['missatge'] = $this->missatge;
        }
        
        if ($this->id_referit !== null) {
            $data['id_referit'] = $this->id_referit;
        }
        
        if ($this->id_referidor !== null) {
            $data['id_referidor'] = $this->id_referidor;
        }
        
        if ($this->id_fidelitat !== null) {
            $data['id_fidelitat'] = $this->id_fidelitat;
        }
        
        $result = DB::update(static::$table, $data, ['id' => $this->id]);
        return $result > 0;
    }
    
    /**
     * Find a client by user ID
     */
    public static function findByUserId(int $userId): ?self
    {
        return (new QueryBuilder(static::class))
            ->where('user_id', '=', $userId)
            ->first();
    }
    
    /**
     * Get the user associated with this client
     */
    public function user(): ?User
    {
        if (!$this->user_id) {
            return null;
        }
        
        return User::find($this->user_id);
    }
    
    /**
     * Get the full name of the client
     */
    public function fullName(): string
    {
        return trim("{$this->nom} {$this->cognom}");
    }
    
    /**
     * Check if the client is an admin
     */
    public function isAdmin(): bool
    {
        return $this->rol === 1;
    }
    
    /**
     * Find a client by login name
     * 
     * @param string $nomLogin The login name to search for
     * @return Client|null The client if found, null otherwise
     */
    public static function findByNomLogin(string $nomLogin): ?Client
    {
        return (new QueryBuilder(static::class))
            ->where('nom_login', '=', $nomLogin)
            ->first();
    }

    /**
     * Find a client by email
     * 
     * @param string $email The email to search for
     * @return Client|null The client if found, null otherwise
     */
    public static function findByEmail(string $email): ?Client
    {
        return (new QueryBuilder(static::class))
            ->where('email', '=', $email)
            ->first();
    }
    
}
