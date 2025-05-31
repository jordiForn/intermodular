<?php
namespace App\Http\Middlewares\Cache;

use App\Core\Request;
use App\Core\Response;

/**
 * Cache Control Middleware
 * 
 * This middleware sets cache control headers for responses.
 */
class CacheControlMiddleware
{
    /**
     * Handle the request
     * 
     * @param Request $request The request object
     * @param array $params Parameters including cache settings
     * @return Response|null The response object
     */
    public function handle(Request $request, array $params = []): ?Response
    {
        $cacheType = $params['type'] ?? 'no-cache';
        
        switch ($cacheType) {
            case 'public':
                $maxAge = $params['maxAge'] ?? 3600; // 1 hour
                header("Cache-Control: public, max-age={$maxAge}");
                break;
                
            case 'private':
                $maxAge = $params['maxAge'] ?? 300; // 5 minutes
                header("Cache-Control: private, max-age={$maxAge}");
                break;
                
            case 'no-cache':
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Pragma: no-cache");
                header("Expires: 0");
                break;
        }
        
        return null;
    }
}
