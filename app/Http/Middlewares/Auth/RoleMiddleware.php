<?php
namespace App\Http\Middlewares\Auth;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;

/**
 * Role Middleware
 * 
 * This middleware checks if a user has one of the specified roles.
 */
class RoleMiddleware
{
    /**
     * Handle the request
     * 
     * @param Request $request The request object
     * @param array $params The allowed roles
     * @return Response|null The response object if role check fails
     */
    public function handle(Request $request, array $params = []): ?Response
    {
        // First check if the user is authenticated
        $authMiddleware = new AuthMiddleware();
        $response = $authMiddleware->handle($request);
        
        if ($response !== null) {
            return $response;
        }
        
        // Then check if the user has one of the allowed roles
        $allowedRoles = $params;
        
        if (empty($allowedRoles) || !in_array(Auth::role(), $allowedRoles)) {
            http_response_code(403);
            view('errors.403');
            exit;
        }
        
        return null;
    }
}
