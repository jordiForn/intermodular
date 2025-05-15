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
        $name = $request->name ?? '';
        $surname = $request->surname ?? '';
        $email = $request->email ?? '';
        $username = $request->username ?? '';
        $tlf = $request->tlf ?? '';
        $password = $request->password ?? '';
        $password_confirm = $request->password_confirm ?? '';
        $terms = $request->terms ?? false;

        // Validación de nombre
        if (trim($name) === '') {
            $errors['name'] = 'El nom és obligatori.';
        }

        // Validación de apellido (opcional, elimina si no es necesario)
        if (isset($request->surname) && trim($surname) === '') {
            $errors['surname'] = 'El cognom és obligatori.';
        }

        // Validaciones de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Ha d\'introduir un email vàlid';
        } elseif (Client::where('email', $email)->first()) {
            $errors['email'] = 'Aquest correu ja està registrat.';
        }

        // Validación de nombre de usuario
        if (trim($username) === '') {
            $errors['username'] = 'El nom d\'usuari és obligatori.';
        } elseif (Client::where('nom_login', $username)->first()) {
            $errors['username'] = 'Aquest nom d\'usuari ja està registrat.';
        }

        // Validación de teléfono
        if (trim($tlf) === '') {
            $errors['tlf'] = 'El telèfon és obligatori.';
        }

        // Validaciones de contraseña robusta
        $password_valid = strlen($password) > 7 &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[\W]/', $password);

        if (!$password_valid) {
            $errors['password'] = 'La contrasenya ha de tenir almenys 8 caràcters, una majúscula, una minúscula i un caràcter especial.';
        }

        // Confirmación de contraseña
        if ($password !== $password_confirm) {
            $errors['password_confirm'] = 'Les contrasenyes no coincideixen.';
        }

        // Validación de términos y condiciones
        if (!$terms) {
            $errors['terms'] = 'Ha d\'acceptar els termes i condicions.';
        }

        // Si hay errores, redirigir con los mensajes
        if ($errors) {
            back()->withErrors($errors)->withInput([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'username' => $request->username,
                'tlf' => $request->tlf,
                'terms' => $request->terms
            ])->send();
        }
    }
}
