<?php

namespace App\Core;

class Response
{
    protected array $data = [];
    protected ?string $redirectTo = null;

    public static function view(string $view, array $data = []): void
    {
        Debug::log("Starting view rendering for: $view");
        
        try {
            // Store previous URL
            if (function_exists('session') && function_exists('request')) {
                session()->flash('_previous_url', request()->url());
                Debug::log("Previous URL stored: " . request()->url());
            } else {
                Debug::log("WARNING: session() or request() function not available");
            }
            
            // Extract data to make variables available in the view
            Debug::log("Extracting data with keys: " . implode(', ', array_keys($data)));
            extract($data);
            
            // Load the view content
            Debug::log("Loading view content: $view");
            $content = self::loadView($view, $data);
            Debug::log("View content loaded successfully, length: " . strlen($content));
            
            // Get the layout file path
            $layoutPath = self::getViewPath('layouts/app');
            Debug::log("Using layout: $layoutPath");
            
            // Check if layout file exists and is readable
            if (!file_exists($layoutPath)) {
                throw new \RuntimeException("Layout file not found: $layoutPath");
            }
            
            if (!is_readable($layoutPath)) {
                throw new \RuntimeException("Layout file not readable: $layoutPath");
            }
            
            // Include the layout file
            Debug::log("Including layout file");
            require $layoutPath;
            Debug::log("Layout included successfully");
            
        } catch (\Throwable $e) {
            Debug::log("ERROR in view rendering: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public static function loadView(string $view, array $data = []): string
    {
        Debug::traceView($view, 'loadView', $data);
        
        try {
            // Get the view file path
            $filePath = self::getViewPath($view);
            Debug::log("View file path: $filePath");
            
            // Extract data to make variables available in the view
            extract($data);
            
            // Start output buffering
            Debug::log("Starting output buffering");
            ob_start();
            
            // Include the view file
            Debug::log("Including view file: $filePath");
            require $filePath;
            
            // Get the buffered content and clean the buffer
            $html = ob_get_clean();
            Debug::log("View rendered successfully, content length: " . strlen($html));
            
            return $html;
            
        } catch (\Throwable $e) {
            // Clean the buffer in case of error
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            
            Debug::log("ERROR in loadView: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Realiza una redirección utilizando BASE_URL como prefijo.
     */
    public static function redirect(string $url): self
    {
        Debug::log("Redirecting to: " . BASE_URL . $url);
        
        if (function_exists('session') && function_exists('request')) {
            session()->flash('_previous_url', request()->url());
        }
        
        $response = new self();
        $response->redirectTo = BASE_URL . $url;
        return $response;
    }

    public static function redirectToAbsolute(string $url): self
    {
        Debug::log("Redirecting to absolute URL: $url");
        if (function_exists('session') && function_exists('request')) {
            session()->flash('_previous_url', request()->url());
        }
        $response = new self();
        $response->redirectTo = $url;
        return $response;
    }

    public static function back(): self
    {
        $previousUrl = HOME;
        
        if (function_exists('session')) {
            $previousUrl = session()->getFlash('_previous_url', HOME);
        }
        
        Debug::log("Redirecting back to: $previousUrl");
        
        $response = new self();
        $response->redirectTo = $previousUrl;

        return $response;
    }

    public function with(string $key, $value): self
    {
        Debug::log("Adding flash data: $key");
        
        if (function_exists('session')) {
            session()->flash($key, $value);
        }
        
        return $this;
    }

    public function withErrors(array $errors): self
    {
        Debug::log("Adding flash errors, count: " . count($errors));
        
        if (function_exists('session')) {
            session()->flash('errors', $errors);
        }
        
        return $this;
    }

    public function withInput(array $old): self
    {
        Debug::log("Adding flash input, keys: " . implode(', ', array_keys($old)));
        
        if (function_exists('session')) {
            session()->flash('old', $old);
        }
        
        return $this;
    }

    public function send(): void
    {
        if ($this->redirectTo) {
            Debug::log("Sending redirect to: {$this->redirectTo}");
            header("Location: {$this->redirectTo}");
            exit;
        }
    }

    private static function getViewPath(string $view): string
    {
        Debug::log("Getting view path for: $view");
        
        $ds = DIRECTORY_SEPARATOR;
        
        // Use project_path helper if available
        if (function_exists('project_path')) {
            $basePath = project_path("resources{$ds}views{$ds}");
        } else {
            $basePath = dirname(__DIR__, 2) . "{$ds}resources{$ds}views{$ds}";
        }
        
        Debug::log("Base path for views: $basePath");
        
        // Convert dot notation to directory separator
        $viewPath = str_replace('.', $ds, $view);
        Debug::log("Converted view path: $viewPath");
        
        // Check for PHP file
        $phpPath = $basePath . "{$viewPath}.php";
        Debug::traceFile($phpPath, file_exists($phpPath));
        
        if (file_exists($phpPath)) {
            Debug::log("Found PHP view file: $phpPath");
            return $phpPath;
        }
        
        // Check for HTML file
        $htmlPath = $basePath . "{$viewPath}.html";
        Debug::traceFile($htmlPath, file_exists($htmlPath));
        
        if (file_exists($htmlPath)) {
            Debug::log("Found HTML view file: $htmlPath");
            return $htmlPath;
        }
        
        Debug::log("ERROR: View file not found for: $view");
        throw new \RuntimeException("La vista '{$view}' no fue encontrada.");
    }
}
