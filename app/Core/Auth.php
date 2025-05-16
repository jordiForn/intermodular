<?php
declare(strict_types=1);

namespace App\Core;
use App\Models\User;
use App\Models\Client;

class Auth {

    public static function attempt(array $credentials): bool
    {
        try {
            $nomLogin = $credentials['nom_login'] ?? '';
            $password = $credentials['contrasena'] ?? '';
            
            if (empty($nomLogin) || empty($password)) {
                if (class_exists('\\App\\Core\\Debug')) {
                    Debug::log("Auth attempt failed: Empty username or password");
                }
                return false;
            }
            
            // Find user by nom_login
            $client = Client::where('nom_login', $nomLogin)->first();
            
            if (!$client) {
                if (class_exists('\\App\\Core\\Debug')) {
                    Debug::log("Auth attempt failed: User not found with nom_login: $nomLogin");
                }
                return false;
            }
            
            $user = User::find($client->user_id);
            
            if (!$user) {
                if (class_exists('\\App\\Core\\Debug')) {
                    Debug::log("Auth attempt failed: User record not found for client_id: {$client->id}");
                }
                return false;
            }
            
            if (password_verify($password, $user->password)) {
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
                
                if (class_exists('\\App\\Core\\Debug')) {
                    Debug::log("Auth successful for user: $nomLogin");
                }
                
                return true;
            }
            
            if (class_exists('\\App\\Core\\Debug')) {
                Debug::log("Auth attempt failed: Invalid password for user: $nomLogin");
            }
            
            return false;
        } catch (\Throwable $e) {
            if (class_exists('\\App\\Core\\Debug')) {
                Debug::log("Exception in Auth::attempt: " . $e->getMessage());
                Debug::log("Stack trace: " . $e->getTraceAsString());
            }
            return false;
        }
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
