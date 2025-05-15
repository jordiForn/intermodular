# Middleware System for Gardening Store Project

This directory contains the middleware components for the gardening store project. Middleware are classes that handle requests before they reach the controller, and can modify the request or response as needed.

## Directory Structure

- `Auth/`: Authentication and authorization middleware
- `Security/`: Security-related middleware (CSRF, XSS, etc.)
- `Logging/`: Request and response logging middleware
- `Validation/`: Input validation and sanitization middleware
- `Cache/`: Cache control middleware

## Core Components

- `Middleware.php`: Base middleware class with static methods for common operations
- `MiddlewareHandler.php`: Handles the middleware stack and executes middleware in order
- `MiddlewareRegistry.php`: Registry of available middleware with methods to apply them

## Usage Examples

### Basic Authentication

\`\`\`php
// In a controller file
use App\Http\Middlewares\Middleware;

// Check if user is authenticated
Middleware::auth();
\`\`\`

### Role-Based Authorization

\`\`\`php
// In a controller file
use App\Http\Middlewares\Middleware;

// Check if user has admin or manager role
Middleware::role(['admin', 'manager']);
\`\`\`

### Using the Middleware Registry

\`\`\`php
// In a controller file
use App\Http\Middlewares\MiddlewareRegistry;
use App\Core\Request;

$request = new Request();

// Apply specific middleware
MiddlewareRegistry::apply($request, [
'auth',
'role' => ['admin'],
'csrf'
]);
\`\`\`

### Adding CSRF Protection to Forms

\`\`\`php
// In a view file
use App\Http\Middlewares\Security\CsrfMiddleware;

// Generate a CSRF token field
echo CsrfMiddleware::tokenField();
\`\`\`

## Creating Custom Middleware

To create a custom middleware, create a new class that implements the `handle` method:

\`\`\`php
namespace App\Http\Middlewares\Custom;

use App\Core\Request;
use App\Core\Response;

class CustomMiddleware
{
public function handle(Request $request, array $params = []): ?Response
{
// Middleware logic here

        // Return null to continue to the next middleware
        // Return a Response object to stop execution and return the response
        return null;
    }

}
\`\`\`

Then register it in the `MiddlewareRegistry`:

\`\`\`php
use App\Http\Middlewares\MiddlewareRegistry;
use App\Http\Middlewares\Custom\CustomMiddleware;

MiddlewareRegistry::register('custom', CustomMiddleware::class);
\`\`\`

## Configuration

Global middleware can be configured in the `MiddlewareRegistry` class. By default, XSS protection and input sanitization are applied to all requests.
