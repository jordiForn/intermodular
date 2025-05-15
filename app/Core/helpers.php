<?php
declare(strict_types=1);

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

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
    Response::view($view, $data);
}

/**
 * Realiza una redirección utilizando BASE_URL como prefijo.
 */
function redirect(string $url): Response
{
    return Response::redirect($url);
}

/**
 * Redirige a la URL anterior. Si no está disponible, redirige a HOME.
 */
function back(): Response
{
    return Response::back();
}

/**
 * Devuelve la URL de la página anterior.
 *
 * Si no hay una referencia HTTP disponible, devuelve HOME.
 */
function previousUrl(): string
{
    return $_SERVER['HTTP_REFERER'] ?? HOME;
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
    if (empty($imageName)) {
        return "/placeholder.svg?height={$height}&width={$width}";
    }
    
    $imagePath = __DIR__ . '/../../public/images/' . $imageName;
    
    if (file_exists($imagePath)) {
        return BASE_URL . '/images/' . $imageName;
    }
    
    return "/placeholder.svg?height={$height}&width={$width}";
}
