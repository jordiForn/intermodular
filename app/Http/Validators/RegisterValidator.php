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
        
        // Validación de nombre
        if (trim($name) === '') {
            $errors['name'] = 'El nom és obligatori.';
        }

        // Validaciones de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Ha d\'introduir un email vàlid';
        }

        // Validación de nombre de usuario
        if (trim($username) === '') {
            $errors['username'] = 'El nom d\'usuari és obligatori.';
        }
        
        // Validación de teléfono
        if (trim($tlf) === '') {
            $errors['tlf'] = 'El telèfon és obligatori.';
        }
        
        // Validación de usuario existente
        $existingUser = Client::where('nom_login', $username)->first();
        if ($existingUser) {
            $errors['username'] = 'Aquest nom d\'usuari ja està registrat.';
        }

        // Validaciones de contraseña
        if (strlen($password) < 6) {
            $errors['password'] = 'La contrasenya ha de tenir almenys 6 caràcters.';
        }

        // Confirmación de contraseña
        if ($password !== $password_confirm) {
            $errors['password_confirm'] = 'Les contrasenyes no coincideixen.';
        }

        // Si hay errores, redirigir con los mensajes
        if ($errors) {
            back()->withErrors($errors)->withInput([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'username' => $request->username,
                'tlf' => $request->tlf
            ])->send();
        }
    }
}
