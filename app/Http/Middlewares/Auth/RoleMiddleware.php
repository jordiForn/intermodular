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
        $user = Auth::user();

        if (empty($allowedRoles) || !$user || !in_array($user->role, $allowedRoles)) {
    header('Location: ' . BASE_URL . '/auth/show-login.php?error=forbidden');
    exit;
}

        return null;
    }
}
