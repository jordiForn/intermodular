<?php
namespace App\Http\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Http\Middlewares\Auth\AuthMiddleware;
use App\Http\Middlewares\Auth\RoleMiddleware;
use App\Http\Middlewares\Security\CsrfMiddleware;
use App\Http\Middlewares\Security\XssProtectionMiddleware;
use App\Http\Middlewares\Logging\RequestLoggerMiddleware;
use App\Http\Middlewares\Validation\InputSanitizerMiddleware;
use App\Http\Middlewares\Cache\CacheControlMiddleware;

/**
 * Middleware Registry
 * 
 * This class provides a registry of available middleware and methods to apply them.
 */
class MiddlewareRegistry
{
    /**
     * @var array Map of middleware aliases to class names
     */
    private static array $middlewareMap = [
        'auth' => AuthMiddleware::class,
        'role' => RoleMiddleware::class,
        'csrf' => CsrfMiddleware::class,
        'xss' => XssProtectionMiddleware::class,
        'logger' => RequestLoggerMiddleware::class,
        'sanitize' => InputSanitizerMiddleware::class,
        'cache' => CacheControlMiddleware::class
    ];
    
    /**
     * @var array Default middleware to apply to all requests
     */
    private static array $globalMiddleware = [
        'xss',
        'sanitize'
    ];
    
    /**
     * Apply global middleware to a request
     * 
     * @param Request $request The request object
     * @return Response|null The response object if middleware generates one
     */
    public static function applyGlobalMiddleware(Request $request): ?Response
    {
        $handler = new MiddlewareHandler($request);
        
        foreach (self::$globalMiddleware as $middleware) {
            if (isset(self::$middlewareMap[$middleware])) {
                $handler->add(self::$middlewareMap[$middleware]);
            }
        }
        
        return $handler->handle();
    }
    
    /**
     * Apply specific middleware to a request
     * 
     * @param Request $request The request object
     * @param array $middleware Array of middleware aliases and parameters
     * @return Response|null The response object if middleware generates one
     */
    public static function apply(Request $request, array $middleware): ?Response
    {
        $handler = new MiddlewareHandler($request);
        
        foreach ($middleware as $name => $params) {
            if (is_int($name)) {
                $name = $params;
                $params = [];
            }
            
            if (isset(self::$middlewareMap[$name])) {
                $handler->add(self::$middlewareMap[$name], $params);
            } elseif (method_exists(Middleware::class, $name)) {
                $handler->add($name, $params);
            }
        }
        
        return $handler->handle();
    }
    
    /**
     * Register a new middleware
     * 
     * @param string $alias The middleware alias
     * @param string $class The middleware class name
     * @return void
     */
    public static function register(string $alias, string $class): void
    {
        self::$middlewareMap[$alias] = $class;
    }
    
    /**
     * Add a middleware to the global middleware stack
     * 
     * @param string $middleware The middleware alias
     * @return void
     */
    public static function addGlobal(string $middleware): void
    {
        if (!in_array($middleware, self::$globalMiddleware)) {
            self::$globalMiddleware[] = $middleware;
        }
    }
    
    /**
     * Remove a middleware from the global middleware stack
     * 
     * @param string $middleware The middleware alias
     * @return void
     */
    public static function removeGlobal(string $middleware): void
    {
        $key = array_search($middleware, self::$globalMiddleware);
        
        if ($key !== false) {
            unset(self::$globalMiddleware[$key]);
            self::$globalMiddleware = array_values(self::$globalMiddleware);
        }
    }
}
