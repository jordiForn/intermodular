<?php
declare(strict_types=1);

namespace App\Http\Validators;
use App\Core\Request;

class LoginValidator
{
    public static function validate(Request $request): void
    {
        $errors = [];

        $nomLogin = $request->nom_login ?? '';
        $contrasena = $request->contrasena ?? '';

        $nomLoginValid = trim($nomLogin) !== '';
        $contrasenaValid = strlen($contrasena) > 7 &&
            preg_match('/[a-z]/', $contrasena) &&
            preg_match('/[A-Z]/', $contrasena) &&
            preg_match('/[\W]/', $contrasena);
    
        if (!$nomLoginValid) {
            $errors['nom_login'] = 'El nom d\'usuari és obligatori';
        }

        if (!$contrasenaValid) {
            $errors['contrasena'] = 'La contrasenya ha de tenir almenys 8 caràcters, una majúscula, una minúscula i un caràcter especial.';
        }

        if ($errors) {
            back()->withErrors($errors)->withInput([
                'nom_login' => $request->nom_login,
            ])->send();
        }
    }
}
