<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    private static ?User $user = null;
    private static bool $initialized = false;
    
    public static function initialize(): void
    {
        if (self::$initialized) {
            return;
        }
        
        self::$initialized = true;
        
        if (session()->has('user_id')) {
            $userId = session()->get('user_id');
            self::$user = User::find($userId);
            
            // If user not found, clear session
            if (!self::$user) {
                session()->forget('user_id');
            }
        }
    }
    
    public static function attempt(array $credentials): bool
    {
        // Check if we're using the legacy system (client table)
        if (isset($credentials['nom_login'])) {
            $username = $credentials['nom_login'];
            $password = $credentials['contrasena'];
            
            // Try to find user by username in users table
            $user = User::findByUsername($username);
            
            if (!$user) {
                return false;
            }
            
            // Verify password
            if (!password_verify($password, $user->password)) {
                return false;
            }
            
            // Login user
            self::login($user);
            
            return true;
        }
        
        // Modern system (users table)
        $username = $credentials['username'];
        $password = $credentials['password'];
        
        // Find user by username
        $user = User::findByUsername($username);
        
        if (!$user) {
            return false;
        }
        
        // Verify password
        if (!password_verify($password, $user->password)) {
            return false;
        }
        
        // Login user
        self::login($user);
        
        return true;
    }
    
    public static function login(User $user): void
    {
        self::$user = $user;
        session()->put('user_id', $user->id);
    }
    
    public static function logout(): void
    {
        self::$user = null;
        session()->forget('user_id');
    }
    
    public static function check(): bool
    {
        self::initialize();
        return self::$user !== null;
    }
    
    public static function user(): ?User
    {
        self::initialize();
        return self::$user;
    }
    
    public static function id(): ?int
    {
        self::initialize();
        return self::$user ? self::$user->id : null;
    }
    
    public static function guest(): bool
    {
        return !self::check();
    }
    
    // Helper method to check if user has admin role
    public static function isAdmin(): bool
    {
        self::initialize();
        return self::$user && self::$user->role === 'admin';
    }
}
