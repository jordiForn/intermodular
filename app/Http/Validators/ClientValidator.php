<?php
declare(strict_types=1);

namespace App\Http\Validators;
use App\Core\Request;
use App\Models\Client;

class ClientValidator
{
    public static function validate(Request $request): void
    {
        $errors = [];

        $nom_valid = trim($request->nom ?? '') !== '';
        $email_valid = filter_var($request->email, FILTER_VALIDATE_EMAIL);
        $tlf_valid = trim($request->tlf ?? '') !== '';
        $nom_login_valid = trim($request->nom_login ?? '') !== '';
        
        if (!$nom_valid) {
            $errors['nom'] = 'El nom és obligatori.';
        }

        if (!$email_valid) {
            $errors['email'] = 'L\'email no és vàlid.';
        }

        if (!$tlf_valid) {
            $errors['tlf'] = 'El telèfon és obligatori.';
        }

        if (!$nom_login_valid) {
            $errors['nom_login'] = 'El nom d\'usuari és obligatori.';
        }

        // Check if email already exists (for new clients)
        if (!isset($request->id) && Client::findByEmail($request->email)) {
            $errors['email'] = 'Aquest email ja està registrat.';
        }

        // Check if username already exists (for new clients)
        if (!isset($request->id) && Client::findByNomLogin($request->nom_login)) {
            $errors['nom_login'] = 'Aquest nom d\'usuari ja està en ús.';
        }

        if ($errors) {
            back()->withErrors($errors)->withInput([
                'nom' => $request->nom,
                'cognom' => $request->cognom,
                'email' => $request->email,
                'tlf' => $request->tlf,
                'consulta' => $request->consulta,
                'missatge' => $request->missatge,
                'nom_login' => $request->nom_login,
            ])->send();
        }
    }
}
