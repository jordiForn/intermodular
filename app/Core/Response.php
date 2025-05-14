<?php

namespace App\Core;

class Response
{
    protected array $data = [];
    protected ?string $redirectTo = null;

    public static function view(string $view, array $data = []): void
    {
        session()->flash('_previous_url', request()->url());
        extract($data);
        $content = self::loadView($view, $data);
        require self::getViewPath('layouts/app');
    }

    public static function loadView(string $view, array $data = []): string
    {
        $filePath = self::getViewPath($view);
        extract($data);
        ob_start();             // Inicia el buffering de salida
        require $filePath;      // Incluye la vista, lo que generará el HTML
        $html = ob_get_clean(); // Obtiene el contenido generado y limpia el buffer

        return $html;           // Devuelve el contenido HTML como una cadena
    }

    /**
     * Realiza una redirección utilizando BASE_URL como prefijo.
     */
    public static function redirect(string $url): self
    {
        session()->flash('_previous_url', request()->url());
        $response = new self();
        $response->redirectTo = BASE_URL . $url;
        return $response;
    }

    public static function back(): self
    {
        $previousUrl = session()->getFlash('_previous_url', HOME);

        $response = new self();
        $response->redirectTo = $previousUrl;

        return $response;
    }

    public function with(string $key, $value): self
    {
        session()->flash($key, $value);
        return $this;
    }

    public function withErrors(array $errors): self
    {
        session()->flash('errors', $errors);
        return $this;
    }

    public function withInput(array $old): self
    {
        session()->flash('old', $old);
        return $this;
    }

    public function send(): void
    {
        if ($this->redirectTo) {
            header("Location: {$this->redirectTo}");
            exit;
        }
    }


    private static function getViewPath(string $view): string
    {
        $ds = DIRECTORY_SEPARATOR;
        $basePath = __DIR__ . "{$ds}..{$ds}..{$ds}resources{$ds}views{$ds}";
        $viewPath = str_replace('.', $ds, $view);

        $phpPath = $basePath . "{$viewPath}.php";
        if (file_exists($phpPath)) {
            return $phpPath;
        }

        $htmlPath = $basePath . "{$viewPath}.html";
        if (file_exists($htmlPath)) {
            return $htmlPath;
        }

        throw new \RuntimeException("La vista '{$view}' no fue encontrada.");
    }
}
