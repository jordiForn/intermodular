<?php
namespace App\Http\Middlewares;
use App\Core\Auth;
use App\Core\Request;

/**
 * Base Middleware class
 * 
 * This class provides core middleware functionality and static methods
 * for common middleware operations like authentication and role checking.
 */
class Middleware
{
    /**
     * Checks if a user is authenticated
     * Redirects to login page if not authenticated
     * 
     * @return void
     */
    public static function auth(): void
    {
        if (!Auth::check()) {
            redirect('/auth/show-login.php')
                ->with('error', 'Accés no autoritzat. Si us plau, inicia sessió.')
                ->with('redirect_to', $_SERVER['REQUEST_URI'])
                ->send();
        }
    }

    /**
     * Checks if an authenticated user has one of the specified roles
     * Shows a 403 error page if the user doesn't have the required role
     * 
     * @param array $roles Array of allowed roles
     * @return void
     */
    public static function role(array $roles): void
    {
        self::auth();
    
        if (!in_array(Auth::role(), $roles)) {
            http_response_code(403);
            view('errors.403');
            exit;
        }
    }

    /**
     * Checks if the user is an admin
     * Shows a 403 error page if the user is not an admin
     * 
     * @return void
     */
    public static function admin(): void
    {
        self::role(['admin']);
    }

    /**
     * Checks if the request has a valid CSRF token
     * Shows a 403 error page if the token is invalid
     * 
     * @param Request $request The request object
     * @return void
     */
    public static function csrf(Request $request): void
    {
        $token = $request->csrf_token ?? '';
        $storedToken = session()->get('csrf_token');
        
        if (empty($token) || $token !== $storedToken) {
            http_response_code(403);
            view('errors.csrf');
            exit;
        }
    }

    /**
     * Checks if the user owns the specified resource
     * Shows a 403 error page if the user doesn't own the resource
     * 
     * @param object $resource The resource to check ownership of
     * @param string $userIdField The field name that contains the user ID in the resource
     * @return void
     */
    public static function owner(object $resource, string $userIdField = 'client_id'): void
    {
        self::auth();
        
        if (!property_exists($resource, $userIdField) || $resource->$userIdField !== Auth::id()) {
            http_response_code(403);
            view('errors.403');
            exit;
        }
    }
}
