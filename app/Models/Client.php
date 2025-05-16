<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\QueryBuilder;

class Client extends Model
{
    protected static string $table = 'client';
    protected static string $primaryKey = 'id';
    
    public int $id;
    public ?int $user_id;
    public ?string $nom;
    public ?string $cognom;
    public ?string $email;
    public ?string $tlf;
    public ?string $consulta;
    public ?string $missatge;
    public ?string $nom_login;
    public ?string $contrasena;
    public int $rol = 0;
    
    /**
     * Find a client by user ID
     * 
     * @param int $userId The user ID to search for
     * @return Client|null The client if found, null otherwise
     */
    public static function findByUserId(int $userId): ?Client
    {
        return (new QueryBuilder(static::class))
            ->where('user_id', '=', $userId)
            ->first();
    }
    
    /**
     * Get the user associated with this client
     * 
     * @return User|null The user if found, null otherwise
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
     * 
     * @return string The full name
     */
    public function fullName(): string
    {
        return trim("{$this->nom} {$this->cognom}");
    }
    
    /**
     * Check if the client is an admin
     * 
     * @return bool True if the client is an admin, false otherwise
     */
    public function isAdmin(): bool
    {
        return $this->rol === 1;
    }
    
    /**
     * Save the client to the database
     * 
     * @return bool True if the save was successful, false otherwise
     */
    public function save(): bool
    {
        if (isset($this->id) && $this->id > 0) {
            // Update existing client
            return $this->update();
        } else {
            // Insert new client
            return $this->insert();
        }
    }
}
