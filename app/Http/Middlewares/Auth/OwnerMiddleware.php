<?php
namespace App\Http\Middlewares\Auth;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;

/**
 * Owner Middleware
 * 
 * This middleware checks if a user owns the specified resource.
 */
class OwnerMiddleware
{
    /**
     * Handle the request
     * 
     * @param Request $request The request object
     * @param array $params Parameters including the resource and user ID field
     * @return Response|null The response object if ownership check fails
     */
    public function handle(Request $request, array $params = []): ?Response
    {
        // First check if the user is authenticated
        $authMiddleware = new AuthMiddleware();
        $response = $authMiddleware->handle($request);
        
        if ($response !== null) {
            return $response;
        }
        
        // Then check if the user owns the resource
        $resource = $params['resource'] ?? null;
        $userIdField = $params['userIdField'] ?? 'client_id';
        
        if ($resource === null || !property_exists($resource, $userIdField) || $resource->$userIdField !== Auth::id()) {
            http_response_code(403);
            view('errors.403');
            exit;
        }
        
        return null;
    }
}
