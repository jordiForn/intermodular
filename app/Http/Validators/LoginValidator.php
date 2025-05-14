<?php
declare(strict_types=1);

namespace App\Http\Validators;
use App\Core\Request;

class LoginValidator
{
    public static function validate(Request $request): void
    {
        $errors = [];

        $username = $request->username ?? '';
        $password = $request->password ?? '';

        if (empty($username)) {
            $errors['username'] = 'El nom d\'usuari és obligatori.';
        }

        if (empty($password)) {
            $errors['password'] = 'La contrasenya és obligatòria.';
        }

        if ($errors) {
            back()->withErrors($errors)->withInput([
                'username' => $request->username,
            ])->send();
        }
    }
}
