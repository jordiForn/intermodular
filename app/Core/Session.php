<?php

namespace App\Core;

class Session {

    /**
     * Inicia la sesión si no está ya iniciada.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Obtiene un valor de la sesión
     */
    public function get(string $key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Establece un valor en la sesión
     */
    public function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    /**
     * Verifica si existe una variable de sesión y es distinta de null
     */
    public function has(string $key): bool {
        return isset($_SESSION[$key]);
    }

    /** 
     * Elimina una variable de sesión
     */ 
    public function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    /**
     * Obtiene el valor de una variable de sesión flash y lo elimina.
     */
    public function getFlash(string $key, $default = null) {
        if (!isset($_SESSION['_flash'])) {
            return $default;
        }
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    /**
     * Establece un valor flash.
     */
    public function flash(string $key, $value): void {
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Verifica si existe un valor flash.
     */
    public function hasFlash(string $key): bool {
        return isset($_SESSION['_flash']) && array_key_exists($key, $_SESSION['_flash']);
    }

    /**
    * Reinicia completamente la sesión actual.
    */
    public function invalidate(): void {
        session_unset(); 
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }
}
