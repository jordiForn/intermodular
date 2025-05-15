<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use App\Core\QueryBuilder;

class User extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = ['username', 'email', 'password', 'role', 'created_at', 'updated_at'];
    protected static array $relations = ['client'];

    /** @override */
    public function insert(): void
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO " . self::$table 
            . " (username, email, password, role, created_at, updated_at)"
            . " VALUES (?, ?, ?, ?, ?, ?)";
        $params = [
            $this->username, 
            $this->email, 
            $this->password, 
            $this->role, 
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
            . " SET username = ?, email = ?, password = ?, role = ?, updated_at = ?"
            . " WHERE id = ?";
        $params = [
            $this->username, 
            $this->email, 
            $this->password, 
            $this->role, 
            $this->updated_at, 
            $this->id
        ];
        DB::update($sql, $params);
    }

    public function client(): ?Client
    {
        return Client::where('user_id', $this->id)->first();
    }

    public static function findByUsername(string $username): ?self
    {
        return self::where('username', $username)->first();
    }

    public static function findByEmail(string $email): ?self
    {
        return self::where('email', $email)->first();
    }
}
