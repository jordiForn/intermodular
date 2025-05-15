<?php
namespace App\Http\Middlewares\Security;

use App\Core\Request;
use App\Core\Response;

/**
 * CSRF Middleware
 * 
 * This middleware checks if the request has a valid CSRF token.
 */
class CsrfMiddleware
{
    /**
     * Handle the request
     * 
     * @param Request $request The request object
     * @param array $params Additional parameters
     * @return Response|null The response object if CSRF check fails
     */
    public function handle(Request $request, array $params = []): ?Response
    {
        // Skip CSRF check for GET requests
        if ($request->method() === 'GET') {
            return null;
        }
        
        $token = $request->csrf_token ?? '';
        $storedToken = session()->get('csrf_token');
        
        if (empty($token) || $token !== $storedToken) {
            http_response_code(403);
            view('errors.csrf');
            exit;
        }
        
        return null;
    }
    
    /**
     * Generate a new CSRF token and store it in the session
     * 
     * @return string The generated token
     */
    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        session()->set('csrf_token', $token);
        return $token;
    }
    
    /**
     * Get the current CSRF token
     * 
     * @return string The current token
     */
    public static function getToken(): string
    {
        $token = session()->get('csrf_token');
        
        if (empty($token)) {
            $token = self::generateToken();
        }
        
        return $token;
    }
    
    /**
     * Generate a hidden input field with the CSRF token
     * 
     * @return string HTML for the CSRF token input
     */
    public static function tokenField(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
