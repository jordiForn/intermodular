<?php
declare(strict_types=1);

namespace App\Core;
use App\Models\User;
use App\Models\Client;

class Auth {

    public static function attempt(array $credentials): bool
    {
        $nomLogin = $credentials['nom_login'] ?? '';
        $password = $credentials['contrasena'] ?? '';
        
        // Find user by nom_login
        $client = Client::where('nom_login', $nomLogin)->first();
        
        if (!$client) {
            return false;
        }
        
        $user = User::find($client->user_id);
        
        if ($user && password_verify($password, $user->password)) {
            // Set session data
            session()->set('user', [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'client_id' => $client->id,
                'nom' => $client->nom,
                'nom_login' => $client->nom_login,
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
