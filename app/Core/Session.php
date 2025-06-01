<?php

namespace App\Core;

class Session {

    private static bool $sessionStarted = false;

    /**
     * Inicia la sesión si no está ya iniciada.
     */
    public function __construct()
    {
        $this->startSession();
    }
    
    /**
     * Start the session if it hasn't been started yet
     */
    private function startSession(): void
    {
        if (self::$sessionStarted) {
            return;
        }
        
        if (session_status() === PHP_SESSION_NONE) {
            // Check if headers have already been sent
            if (!headers_sent()) {
                session_start();
                self::$sessionStarted = true;
                
                if (class_exists('\\App\\Core\\Debug')) {
                    Debug::log("Session started successfully");
                }
            } else {
                // Log the error but don't throw an exception
                if (class_exists('\\App\\Core\\Debug')) {
                    Debug::log("WARNING: Attempted to start session after headers were sent");
                }
            }
        } else {
            self::$sessionStarted = true;
            
            if (class_exists('\\App\\Core\\Debug')) {
                Debug::log("Session was already active");
            }
        }
    }

    

    /**
     * Obtiene un valor de la sesión
     */
    public function get(string $key, $default = null) {
        $this->startSession();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Establece un valor en la sesión
     */
    public function set(string $key, $value): void {
        $this->startSession();
        $_SESSION[$key] = $value;
        
        if (class_exists('\\App\\Core\\Debug')) {
            Debug::log("Session value set: $key");
        }
    }

    /**
     * Alias for set() to maintain compatibility
     */
    public function put(string $key, $value): void {
        $this->set($key, $value);
    }

    /**
     * Verifica si existe una variable de sesión y es distinta de null
     */
    public function has(string $key): bool {
        $this->startSession();
        return isset($_SESSION[$key]);
    }

    /**
     * Elimina una variable de sesión
     */ 
    public function remove(string $key): void {
        $this->startSession();
        unset($_SESSION[$key]);
        
        if (class_exists('\\App\\Core\\Debug')) {
            Debug::log("Session value removed: $key");
        }
    }

    /**
     * Alias for remove() to maintain compatibility
     */
    public function forget(string $key): void {
        $this->remove($key);
    }

    /**
     * Obtiene el valor de una variable de sesión flash y lo elimina.
     */
    public function getFlash(string $key, $default = null) {
        $this->startSession();
        if (!isset($_SESSION['_flash'])) {
            return $default;
        }
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        
        if (class_exists('\\App\\Core\\Debug')) {
            Debug::log("Flash value retrieved and removed: $key");
        }
        
        return $value;
    }

    /**
     * Establece un valor flash.
     */
    public function flash(string $key, $value): void {
        $this->startSession();
        if (!isset($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }
        $_SESSION['_flash'][$key] = $value;
        
        if (class_exists('\\App\\Core\\Debug')) {
            Debug::log("Flash value set: $key");
        }
    }

    /**
     * Verifica si existe un valor flash.
     */
    public function hasFlash(string $key): bool {
        $this->startSession();
        return isset($_SESSION['_flash']) && array_key_exists($key, $_SESSION['_flash']);
    }

    /**
    * Reinicia completamente la sesión actual.
    */
    public function invalidate(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset(); 
            session_destroy();
            self::$sessionStarted = false;
            
            if (class_exists('\\App\\Core\\Debug')) {
                Debug::log("Session invalidated");
            }
            
            // Only try to start a new session if headers haven't been sent
            if (!headers_sent()) {
                session_start();
                session_regenerate_id(true);
                self::$sessionStarted = true;
                
                if (class_exists('\\App\\Core\\Debug')) {
                    Debug::log("New session started after invalidation");
                }
            }
        }
    }
    
    /**
     * Dump all session data for debugging
     */
    public function dump(): array {
        $this->startSession();
        return $_SESSION;
    }
}
