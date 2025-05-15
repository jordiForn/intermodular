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

        $password_valid = strlen($password) > 7 &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[\W]/', $password);

        if (empty($password)) {
            $errors['password'] = 'La contrasenya és obligatòria.';
        } elseif (!$password_valid) {
            $errors['password'] = 'La contrasenya ha de tenir almenys 8 caràcters, una majúscula, una minúscula i un caràcter especial.';
        }

        if ($errors) {
            back()->withErrors($errors)->withInput([
                'username' => $request->username,
            ])->send();
        }
    }
}
