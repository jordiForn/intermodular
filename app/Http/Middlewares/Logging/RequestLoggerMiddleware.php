<?php
namespace App\Http\Middlewares\Logging;

use App\Core\Request;
use App\Core\Response;

/**
 * Request Logger Middleware
 * 
 * This middleware logs information about incoming requests.
 */
class RequestLoggerMiddleware
{
    /**
     * Handle the request
     * 
     * @param Request $request The request object
     * @param array $params Additional parameters
     * @return Response|null The response object
     */
    public function handle(Request $request, array $params = []): ?Response
    {
        $logFile = $params['logFile'] ?? __DIR__ . '/../../../../logs/requests.log';
        $logLevel = $params['logLevel'] ?? 'info';
        
        // Create logs directory if it doesn't exist
        $logsDir = dirname($logFile);
        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }
        
        // Only log detailed information for higher log levels
        if ($logLevel === 'debug') {
            $logData = [
                'time' => date('Y-m-d H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'method' => $request->method(),
                'url' => $request->url(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                'referer' => $_SERVER['HTTP_REFERER'] ?? 'None',
                'user_id' => session()->get('user.id') ?? 'Not logged in'
            ];
        } else {
            $logData = [
                'time' => date('Y-m-d H:i:s'),
                'method' => $request->method(),
                'url' => $request->url(),
                'user_id' => session()->get('user.id') ?? 'Not logged in'
            ];
        }
        
        $logMessage = json_encode($logData) . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
        
        return null;
    }
}
