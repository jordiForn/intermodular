<?php
namespace App\Http\Middlewares\Validation;

use App\Core\Request;
use App\Core\Response;

/**
 * Input Sanitizer Middleware
 * 
 * This middleware sanitizes input data to prevent security issues.
 */
class InputSanitizerMiddleware
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
        // Get all input data
        $data = array_merge($_GET, $_POST);
        
        // Sanitize each field
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Trim whitespace
                $value = trim($value);
                
                // Convert special characters to HTML entities
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                
                // Update the request data
                if (isset($_GET[$key])) {
                    $_GET[$key] = $value;
                }
                
                if (isset($_POST[$key])) {
                    $_POST[$key] = $value;
                }
            }
        }
        
        return null;
    }
}
