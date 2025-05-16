<?php
declare(strict_types=1);

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Debug;
use App\Core\Auth;

//
// Instancias reutilizables relacionadas con la petición
//

/**
 * Devuelve una instancia única de la clase Request para toda la petición.
 *
 * Esta función actúa como un singleton *sin forzar* que la clase Request lo sea.
 * Se puede seguir instanciando Request en otros contextos, como en pruebas.
 */
function request(): Request
{
    static $instance = null;
    if ($instance === null) {
        $instance = new Request();
    }
    return $instance;
}

/**
 * Devuelve una instancia única de la clase Session para la sesión actual.
 *
 * Actúa como acceso global controlado, evitando múltiples instancias no sincronizadas.
 */
function session(): Session
{
    static $instance = null;
    if ($instance === null) {
        $instance = new Session();
    }
    return $instance;
}

/**
 * Helper function for Auth class to make it easier to use in views
 */
function auth(): Auth
{
    return new Auth();
}

//
// Formularios
//

/**
 * Devuelve los valores iniciales de un formulario, priorizando datos antiguos
 * (de una validación fallida anterior) y luego el modelo actual (si existe).
 *
 * @param array $fields Lista de nombres de campos a recuperar.
 * @param object|null $model Objeto del modelo (con propiedades públicas o accesibles).
 * @return array Valores iniciales para el formulario (clave => valor).
 */
function formDefaults(array $fields, ?object $model = null): array
{
    $session = new Session();
    $old = $session->getFlash('old', []);
    $values = [];

    foreach ($fields as $field) {
        $values[$field] = $old[$field] ?? $model?->$field ?? '';
    }

    return $values;
}

/**
 * Escapa todos los valores de un array asociativo para uso en HTML.
 * Solo escapa si el valor es una cadena.
 */
function escapeArray(array $data): array
{
    return array_map(function ($value) {
        return is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
    }, $data);
}

//
// Respuestas y vistas
//

/**
 * Renderiza una vista con los datos proporcionados.
 */
function view(string $view, array $data = []): void
{
    try {
        if (class_exists('\\App\\Core\\Debug')) {
            \App\Core\Debug::log("Helper view() called for: $view");
        }
        
        Response::view($view, $data);
        
        if (class_exists('\\App\\Core\\Debug')) {
            \App\Core\Debug::log("Helper view() completed successfully for: $view");
        }
    } catch (\Throwable $e) {
        if (class_exists('\\App\\Core\\Debug')) {
            \App\Core\Debug::log("ERROR in helper view(): " . $e->getMessage());
            \App\Core\Debug::log("Stack trace: " . $e->getTraceAsString());
        }
        throw $e;
    }
}

/**
 * Realiza una redirección utilizando BASE_URL como prefijo.
 */
function redirect(string $url): Response
{
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Helper redirect() called for: $url");
    }
    return Response::redirect($url);
}

/**
 * Redirige a la URL anterior. Si no está disponible, redirige a HOME.
 */
function back(): Response
{
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Helper back() called");
    }
    return Response::back();
}

/**
 * Devuelve la URL de la página anterior.
 *
 * Si no hay una referencia HTTP disponible, devuelve HOME.
 */
function previousUrl(): string
{
    $referer = $_SERVER['HTTP_REFERER'] ?? HOME;
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Helper previousUrl() called, returning: $referer");
    }
    return $referer;
}

/**
 * Genera una URL para una imagen, verificando si existe.
 * Si la imagen no existe, devuelve una imagen de placeholder.
 * 
 * @param string|null $imageName Nombre del archivo de imagen
 * @param int $width Ancho del placeholder si la imagen no existe
 * @param int $height Alto del placeholder si la imagen no existe
 * @return string URL de la imagen
 */
function imageUrl(?string $imageName, int $width = 300, int $height = 300): string
{
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Helper imageUrl() called for: $imageName");
    }
    
    if (empty($imageName)) {
        if (class_exists('\\App\\Core\\Debug')) {
            \App\Core\Debug::log("Empty image name, returning placeholder");
        }
        return "/placeholder.svg?height={$height}&width={$width}";
    }
    
    $imagePath = project_path('public/images/' . $imageName);
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Checking image path: $imagePath");
    }
    
    if (file_exists($imagePath)) {
        if (class_exists('\\App\\Core\\Debug')) {
            \App\Core\Debug::log("Image exists, returning URL: " . BASE_URL . '/images/' . $imageName);
        }
        return BASE_URL . '/images/' . $imageName;
    }
    
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Image not found, returning placeholder");
    }
    return "/placeholder.svg?height={$height}&width={$width}";
}

/**
 * Trigger an HTTP error with the specified status code
 * 
 * @param int $statusCode The HTTP status code
 * @param string|null $message Optional custom message
 * @return void
 */
function http_error(int $statusCode, ?string $message = null)
{
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Helper http_error() called with status: $statusCode, message: $message");
    }
    \App\Core\ErrorHandler::handleHttpError($statusCode, $message);
}

/**
 * Get the absolute path to a file in the project
 * 
 * @param string $path Relative path from project root
 * @return string Absolute path
 */
function project_path(string $path = ''): string
{
    // First, try to get the project root using __DIR__
    $rootDir = null;
    
    // Check if we're in app/Core
    if (basename(dirname(__DIR__)) === 'app' && basename(__DIR__) === 'Core') {
        $rootDir = dirname(__DIR__, 2); // Go up two levels from app/Core
    } 
    // Check if we're in public
    elseif (basename(__DIR__) === 'public') {
        $rootDir = dirname(__DIR__); // Go up one level from public
    }
    // Check if we're in bootstrap
    elseif (basename(__DIR__) === 'bootstrap') {
        $rootDir = dirname(__DIR__); // Go up one level from bootstrap
    }
    // Check if PROJECT_ROOT is defined
    elseif (defined('PROJECT_ROOT')) {
        $rootDir = PROJECT_ROOT;
    }
    // Fallback: try to find the project root by looking for key directories
    else {
        // Start from the current directory and go up until we find a directory that looks like the project root
        $currentDir = __DIR__;
        $maxLevels = 5; // Prevent infinite loops
        
        for ($i = 0; $i < $maxLevels; $i++) {
            // Check if this looks like the project root (has app, public, and resources directories)
            if (is_dir($currentDir . '/app') && is_dir($currentDir . '/public') && is_dir($currentDir . '/resources')) {
                $rootDir = $currentDir;
                break;
            }
            
            // Go up one level
            $parentDir = dirname($currentDir);
            if ($parentDir === $currentDir) {
                // We've reached the filesystem root without finding the project root
                break;
            }
            
            $currentDir = $parentDir;
        }
    }
    
    // If we still don't have a root directory, use a best guess based on the server document root
    if ($rootDir === null) {
        if (isset($_SERVER['DOCUMENT_ROOT'])) {
            // Assume the project root is one level up from the document root/intermodular/public
            $rootDir = dirname(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . '/intermodular/public');
        } else {
            // Last resort: use the current directory
            $rootDir = __DIR__;
            
            // Log this issue
            if (function_exists('error_log')) {
                error_log("WARNING: Could not determine project root in project_path(). Using current directory: $rootDir");
            }
        }
    }
    
    // Normalize directory separators
    $rootDir = str_replace('\\', '/', $rootDir);
    $path = str_replace('\\', '/', $path);
    
    // Combine the root directory with the path
    $fullPath = $rootDir . ($path ? '/' . ltrim($path, '/') : '');
    
    // Log the path resolution if Debug class is available
    if (class_exists('\\App\\Core\\Debug') && method_exists('\\App\\Core\\Debug', 'log')) {
        try {
            \App\Core\Debug::log("Helper project_path() called for: $path, returning: $fullPath");
        } catch (\Throwable $e) {
            // Ignore errors in logging
        }
    }
    
    return $fullPath;
}

/**
 * Get the URL for a public asset
 * 
 * @param string $path Path relative to the public directory
 * @return string Full URL to the asset
 */
function asset(string $path): string
{
    $url = BASE_URL . '/' . ltrim($path, '/');
    if (class_exists('\\App\\Core\\Debug')) {
        \App\Core\Debug::log("Helper asset() called for: $path, returning: $url");
    }
    return $url;
}
