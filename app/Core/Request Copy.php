<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Session;

class Request
{

    protected array $data;
    protected array $files;
    protected array $server;
    protected ?Session $session = null;

    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST);
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    public function __get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }


    public function url(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

    public function routeIs(string $route): bool
    {

        //////////////////////////////////////// USAR TRIM EN VEZ DE PREG_REPLACE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $basePath = parse_url(BASE_URL, PHP_URL_PATH); // Extraer el path (eliminar el dominio)

        $currentRoute = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH); // Quita el query string
        $currentRoute = preg_replace('#^' . preg_quote($basePath) . '#', '', $currentRoute);

        return trim($currentRoute, '/') === trim($route, '/');
    }

    /*
    public function indexRoute(): string
    {

        $basePath = parse_url(BASE_URL, PHP_URL_PATH);
        $currentRoute = preg_replace('#^' . preg_quote($basePath) . '#', '', $this->url());

        $parts = explode('/', trim($currentRoute, '/'));

        return BASE_URL . '/' . $parts[0] . '/index.php';
    }
    */

    public function session(): Session
    {
        if (!$this->session) {
            $this->session = session();
        }
        return $this->session;
    }
}
