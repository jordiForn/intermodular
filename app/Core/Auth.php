<?php
declare(strict_types=1);

namespace App\Core;
use App\Models\User;
use App\Models\Client;

class Auth {

    public static function attempt(array $credentials): bool
    {
        $username = $credentials['email'] ?? '';
        $password = $credentials['contrasena'] ?? '';
        
        // Try to find user by email
        $user = User::findByEmail($username);
        
        // If not found by email, try username
        if (!$user) {
            $user = User::findByUsername($username);
        }
    
        if ($user && password_verify($password, $user->password)) {
            // Get the client associated with this user
            $client = $user->client();
            
            session()->set('user', [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'client_id' => $client ? $client->id : null,
                'nom' => $client ? $client->nom : null,
            ]);
            return true;
        }
        return false;
    }

    public static function user(): ?array {
        return session()->get('user');
    }

    public static function check(): bool {
        return self::user() !== null;
    }

    public static function id(): ?int {
        $user = self::user(); // Obtener el usuario una vez
        return $user ? $user['id'] : null; // Verificar si no es null
    }
    
    public static function clientId(): ?int {
        $user = self::user();
        return $user ? $user['client_id'] : null;
    }
    
    public static function role(): ?string {
        $user = self::user(); // Obtener el usuario una vez
        return $user ? $user['role'] : null; // Verificar si no es null
    }

    public static function logout(): void {
        session()->invalidate();
    }
}
