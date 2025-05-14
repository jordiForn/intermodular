<?php
declare(strict_types=1);

namespace App\Core;
use App\Models\Usuario;

class Auth {

    public static function attempt(array $credentials): bool
    {
        $email = $credentials['email'] ?? '';
        $password = $credentials['password'] ?? '';
        $u = Usuario::where('email', $email)->first();
    
        if ($u && password_verify($password, $u->password)) {
            session()->set('user', [
                'id' => $u->id,
                'nombre' => $u->nombre,
                'email' => $u->email,
                'role' => $u->role,
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
    
    public static function role(): ?string {
        $user = self::user(); // Obtener el usuario una vez
        return $user ? $user['role'] : null; // Verificar si no es null
    }

    public static function logout(): void {
        session()->invalidate();
    }
}