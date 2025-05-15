<?php
namespace App\Http\Middlewares;

use App\Core\Request;
use App\Core\Response;

/**
 * Middleware Handler
 * 
 * This class manages the middleware stack and executes middleware in the correct order.
 */
class MiddlewareHandler
{
    /**
     * @var array The middleware stack
     */
    private array $middlewares = [];
    
    /**
     * @var Request The request object
     */
    private Request $request;
    
    /**
     * @var Response|null The response object
     */
    private ?Response $response = null;

    /**
     * Constructor
     * 
     * @param Request $request The request object
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Add a middleware to the stack
     * 
     * @param string $middleware The middleware class name
     * @param array $params Parameters to pass to the middleware
     * @return self
     */
    public function add(string $middleware, array $params = []): self
    {
        $this->middlewares[] = [
            'class' => $middleware,
            'params' => $params
        ];
        
        return $this;
    }

    /**
     * Execute all middleware in the stack
     * 
     * @return Response|null The response object if one was generated
     */
    public function handle(): ?Response
    {
        foreach ($this->middlewares as $middleware) {
            $class = $middleware['class'];
            $params = $middleware['params'];
            
            // If the middleware is a static method on the Middleware class
            if (method_exists(Middleware::class, $class)) {
                call_user_func_array([Middleware::class, $class], $params);
                continue;
            }
            
            // If the middleware is a class
            if (class_exists($class)) {
                $instance = new $class();
                $response = $instance->handle($this->request, $params);
                
                // If the middleware returned a response, store it and stop execution
                if ($response instanceof Response) {
                    $this->response = $response;
                    break;
                }
            }
        }
        
        return $this->response;
    }
}
