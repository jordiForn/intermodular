<?php
declare(strict_types=1);

/**
 * Auth Facade - Provides a global access point to Auth functionality
 * This allows using Auth:: syntax in views without namespace
 */
class Auth extends \App\Core\Auth
{
    // This class inherits all methods from App\Core\Auth
    // It exists to provide a global access point without namespace
}
