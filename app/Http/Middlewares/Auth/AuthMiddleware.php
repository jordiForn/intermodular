<?php
namespace App\Http\Middlewares\Auth;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;

/**
 * Authentication Middleware
 * 
 * This middleware checks if a user is authenticated.
 */
class AuthMiddleware
{
    /**
     * Handle the request
     * 
     * @param Request $request The request object
     * @param array $params Additional parameters
     * @return Response|null The response object if authentication fails
     */
    public function handle(Request $request, array $params = []): ?Response
    {
        if (!Auth::check()) {
            return redirect('/auth/show-login.php')
                ->with('error', 'Accés no autoritzat. Si us plau, inicia sessió.')
                ->with('redirect_to', $request->url());
        }
        
        return null;
    }
}
