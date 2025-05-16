<?php

namespace App\Core;

/**
 * Class MethodAlias
 * Provides method aliasing functionality for backward compatibility
 */
class MethodAlias
{
    /**
     * @var array Method aliases mapping
     */
    private static $aliases = [];
    
    /**
     * Register a method alias
     *
     * @param string $class The class name
     * @param string $alias The alias method name
     * @param string $target The target method name
     * @return void
     */
    public static function register(string $class, string $alias, string $target): void
    {
        if (!isset(self::$aliases[$class])) {
            self::$aliases[$class] = [];
        }
        
        self::$aliases[$class][$alias] = $target;
    }
    
    /**
     * Check if a method has an alias
     *
     * @param string $class The class name
     * @param string $method The method name
     * @return bool
     */
    public static function hasAlias(string $class, string $method): bool
    {
        return isset(self::$aliases[$class][$method]);
    }
    
    /**
     * Get the target method for an alias
     *
     * @param string $class The class name
     * @param string $method The method name
     * @return string|null
     */
    public static function getTarget(string $class, string $method): ?string
    {
        return self::$aliases[$class][$method] ?? null;
    }
    
    /**
     * Call a method with alias support
     *
     * @param object $object The object instance
     * @param string $method The method name
     * @param array $args The method arguments
     * @return mixed
     * @throws \BadMethodCallException
     */
    public static function call(object $object, string $method, array $args = [])
    {
        $class = get_class($object);
        
        // Check if the method exists directly
        if (method_exists($object, $method)) {
            return $object->$method(...$args);
        }
        
        // Check if there's an alias for this method
        if (self::hasAlias($class, $method)) {
            $targetMethod = self::getTarget($class, $method);
            
            if (method_exists($object, $targetMethod)) {
                return $object->$targetMethod(...$args);
            }
        }
        
        // Method not found
        throw new \BadMethodCallException("Call to undefined method $class::$method()");
    }
}
