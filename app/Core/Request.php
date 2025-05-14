<?php
declare(strict_types=1);

namespace App\Core;

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
        $currentRoute = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH); // Quita el query string
        $basePath = parse_url(BASE_URL, PHP_URL_PATH); // Extraer el path (eliminar el dominio)
        $currentRoute = preg_replace('#^' . preg_quote($basePath) . '#', '', $currentRoute);

        return trim($currentRoute, '/') === trim($route, '/');
    }

    public function session(): Session
    {
        if (!$this->session) {
            $this->session = session();
        }
        return $this->session;
    }

    public static function fake(array $data = [], array $files = [], array $server = []): static
    {
        $instance = new static();
        $instance->data = $data;
        $instance->files = $files;
        $instance->server = $server;
        return $instance;
    }
}
