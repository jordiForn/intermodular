<?php
declare(strict_types=1);

namespace App\Core;

use App\Http\Middlewares\MiddlewareRegistry;

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
        
        // Apply global middleware
        if (class_exists('App\Http\Middlewares\MiddlewareRegistry')) {
            MiddlewareRegistry::applyGlobalMiddleware($this);
        }
    }

    public function __get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }
    
    /**
     * Get an input value from the request
     * 
     * @param string $key The input key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed The input value or default
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
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
    
    /**
     * Apply middleware to this request
     * 
     * @param array $middleware Array of middleware aliases and parameters
     * @return Response|null The response object if middleware generates one
     */
    public function middleware(array $middleware): ?Response
    {
        if (class_exists('App\Http\Middlewares\MiddlewareRegistry')) {
            return MiddlewareRegistry::apply($this, $middleware);
        }
        return null;
    }
    
    /**
     * Get all input data
     * 
     * @return array All input data
     */
    public function all(): array
    {
        return $this->data;
    }
}
