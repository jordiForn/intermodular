<?php
declare(strict_types=1);

namespace App\Http\Validators;
use App\Core\Request;
use App\Models\Client;

class RegisterValidator
{
    public static function validate(Request $request): void
    {
        $errors = [];

        // Obtener datos
        $nom = $request->nom ?? '';
        $email = $request->email ?? '';
        $nomLogin = $request->nom_login ?? '';
        $contrasena = $request->contrasena ?? '';
        $contrasena_confirm = $request->contrasena_confirm ?? '';
        $terms = $request->terms ?? false;  // Verificar si el checkbox está marcado
        
        // Validación de nombre
        if (trim($nom) === '') {
            $errors['nom'] = 'El nom és obligatori.';
        }

        // Validación de nom_login
        if (trim($nomLogin) === '') {
            $errors['nom_login'] = 'El nom d\'usuari és obligatori.';
        } elseif (Client::findByNomLogin($nomLogin)) {
            $errors['nom_login'] = 'Aquest nom d\'usuari ja està en ús.';
        }

        // Validaciones de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Ha d\'introduir un email vàlid';
        }

        if(Client::where('email', $email)->first()){
            $errors['email'] = 'Aquest correu ja està registrat.';
        }

        // Validaciones de contraseña
        $contrasena_valid = strlen($contrasena) > 7 &&
            preg_match('/[a-z]/', $contrasena) &&
            preg_match('/[A-Z]/', $contrasena) &&
            preg_match('/[\W]/', $contrasena);
        
        if (!$contrasena_valid) {
            $errors['contrasena'] = 'La contrasenya ha de tenir almenys 8 caràcters, una majúscula, una minúscula i un caràcter especial.';
        }

        // Confirmación de contraseña
        if ($contrasena !== $contrasena_confirm) {
            $errors['contrasena_confirm'] = 'Les contrasenyes no coincideixen.';
        }

        // Validación de términos y condiciones
        if (!$terms) {
            $errors['terms'] = 'Ha d\'acceptar els termes i condicions.';
        }

        // Si hay errores, redirigir con los mensajes
        if ($errors) {
            back()->withErrors($errors)->withInput([
                'nom' => $request->nom,
                'email' => $request->email,
                'nom_login' => $request->nom_login,
                'terms' => $request->terms
            ])->send();
        }
    }
}
