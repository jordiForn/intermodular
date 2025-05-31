<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    private static ?User $user = null;
    
    /**
     * Attempt to authenticate a user
     */
    public static function attempt(array $credentials): bool
    {
        Debug::log("Auth::attempt called with credentials for: " . ($credentials['nom_login'] ?? $credentials['username'] ?? 'unknown'));
        
        try {
            $username = $credentials['username'];
            $password = $credentials['password'];
            
            // Find user by username
            $user = User::findByUsername($username);
            
            if (!$user) {
                Debug::log("User not found with username: $username");
                return false;
            }
            
            // Verify password
            if (!password_verify($password, $user->password)) {
                Debug::log("Password verification failed for user: $username");
                return false;
            }
            
            // Login user
            self::login($user);
            Debug::log("User logged in successfully: $username");
            
            return true;
        } catch (\Throwable $e) {
            Debug::log("Exception in Auth::attempt: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }
    
    /**
     * Login a user
     */
    public static function login(User $user): void
    {
        Debug::log("Auth::login called for user ID: " . $user->id);
        
        // Store user in session
        session()->set('user_id', $user->id);
        
        // Store user in static property
        self::$user = $user;
        
        Debug::log("User logged in and stored in session: " . $user->username);
    }
    
    /**
     * Logout the current user
     */
    public static function logout(): void
    {
        Debug::log("Auth::logout called");
        
        // Remove user from session
        session()->remove('user_id');
        
        // Clear static property
        self::$user = null;
        
        Debug::log("User logged out and removed from session");
    }
    
    /**
     * Check if a user is logged in
     */
    public static function check(): bool
    {
        // If we already have a user, return true
        if (self::$user !== null) {
            return true;
        }
        
        // Check if user_id is in session
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return false;
        }
        
        // Try to find user by ID
        try {
            $user = User::find($userId);
            
            if (!$user) {
                return false;
            }
            
            // Store user in static property
            self::$user = $user;
            
            return true;
        } catch (\Throwable $e) {
            Debug::log("Exception in Auth::check: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get the current authenticated user
     */
    public static function user(): ?User
    {
        if (self::check()) {
            return self::$user;
        }
        
        return null;
    }
    
    /**
     * Check if the current user is an admin
     */
    public static function isAdmin(): bool
    {
        $user = self::user();
        
        if (!$user) {
            return false;
        }
        
        return $user->role === 'admin';
    }
}
