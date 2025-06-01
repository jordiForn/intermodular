<?php
namespace App\Http\Middlewares\Security;

use App\Core\Request;
use App\Core\Response;

/**
 * XSS Protection Middleware
 * 
 * This middleware adds security headers to prevent XSS attacks.
 */
class XssProtectionMiddleware
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
        // Check if headers have already been sent
        if (headers_sent($file, $line)) {
            error_log("XSS Protection Middleware: Headers already sent in {$file} on line {$line}");
            return null;
        }
        
        // Add security headers
        header('X-XSS-Protection: 1; mode=block');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Add Content Security Policy if enabled
        if ($params['enableCsp'] ?? true) {
            $csp = "default-src 'self'; "
     . "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
     . "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com; "
     . "img-src 'self' data:; "
     . "font-src 'self' https://cdn.jsdelivr.net https://fonts.gstatic.com https://cdnjs.cloudflare.com; "
     . "connect-src 'self';";
            
            header("Content-Security-Policy: {$csp}");
        }
        
        return null;
    }
}
