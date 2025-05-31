<?php

use App\Core\Auth;

// Incluye el autoload si es necesario
require_once __DIR__ . '/../../bootstrap/autoload.php';

// Cierra la sesión del usuario
Auth::logout();

// Mensaje opcional (puedes mostrarlo en la página de inicio con un flash message)
session()->set('success', 'Has cerrado sesión correctamente.');

// Redirige al usuario a la página de login o inicio
header('Location: ' . BASE_URL);
exit;