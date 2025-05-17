<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\QueryBuilder;

class User extends Model
{
    protected static string $table = 'users';
    protected static string $primaryKey = 'id';
    
    public ?int $id;
    public string $username;
    public string $email;
    public string $password;
    public string $role;
    public string $created_at;
    public string $updated_at;
    
    /**
     * Find a user by username
     * 
     * @param string $username The username to search for
     * @return User|null The user if found, null otherwise
     */
    public static function findByUsername(string $username): ?User
    {
        return (new QueryBuilder(static::class))
            ->where('username', '=', $username)
            ->first();
    }
    
    /**
     * Find a user by email
     * 
     * @param string $email The email to search for
     * @return User|null The user if found, null otherwise
     */
    public static function findByEmail(string $email): ?User
    {
        return (new QueryBuilder(static::class))
            ->where('email', '=', $email)
            ->first();
    }
    
    /**
     * Check if the user is an admin
     * 
     * @return bool True if the user is an admin, false otherwise
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    /**
     * Get the client associated with this user
     * 
     * @return Client|null The client if found, null otherwise
     */
    public function client(): ?Client
    {
        return Client::where('user_id', '=', $this->id)->first();
    }
    
    /**
     * Save the user to the database
     * 
     * @return bool True if the save was successful, false otherwise
     */
    public function save(): bool
    {
        if (isset($this->id) && $this->id > 0) {
            // Update existing user
            $this->updated_at = date('Y-m-d H:i:s');
            return $this->update();
        } else {
            // Insert new user
            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = date('Y-m-d H:i:s');
            return $this->insert();
        }
    }

    public function insert(): bool
{
    $data = [
        'username' => $this->username,
        'email' => $this->email,
        'password' => $this->password,
        'role' => $this->role,
        'created_at' => $this->created_at
    ];

    $this->id = \App\Core\DB::insert(static::$table, $data);
    return $this->id !== null && $this->id > 0;
}

public function update(): bool
{
    $data = [
        'username' => $this->username,
        'email' => $this->email,
        'password' => $this->password,
        'role' => $this->role,
        'updated_at' => $this->updated_at,
    ];

    $result = \App\Core\DB::update(static::$table, $data, ['id' => $this->id]);
    return $result > 0;
}

}
