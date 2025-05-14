<?php

namespace App\Exceptions;

class Handler{
    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return view('errors/404', []);
        }
        return view('errors/500', []);
    }
}
