<?php
namespace App\Http\Middlewares\Security;

use App\Core\Request;
use App\Core\Response;

/**
 * Rate Limit Middleware
 * 
 * This middleware limits the number of requests a user can make in a given time period.
 */
class RateLimitMiddleware
{
    /**
     * Handle the request
     * 
     * @param Request $request The request object
     * @param array $params Parameters including max requests and time window
     * @return Response|null The response object if rate limit is exceeded
     */
    public function handle(Request $request, array $params = []): ?Response
    {
        $maxRequests = $params['maxRequests'] ?? 60;
        $timeWindow = $params['timeWindow'] ?? 60; // in seconds
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = "rate_limit:{$ip}";
        
        $requests = session()->get($key, [
            'count' => 0,
            'reset_time' => time() + $timeWindow
        ]);
        
        // Reset counter if the time window has passed
        if (time() > $requests['reset_time']) {
            $requests = [
                'count' => 0,
                'reset_time' => time() + $timeWindow
            ];
        }
        
        // Increment request count
        $requests['count']++;
        session()->set($key, $requests);
        
        // Check if rate limit is exceeded
        if ($requests['count'] > $maxRequests) {
            http_response_code(429);
            header('Retry-After: ' . ($requests['reset_time'] - time()));
            view('errors.rate_limit', [
                'retryAfter' => $requests['reset_time'] - time()
            ]);
            exit;
        }
        
        return null;
    }
}
