<?php
declare(strict_types=1);

namespace App\Http\Validators;
use App\Core\Request;
use App\Models\Client;

class ClientValidator
{
    public static function validate(Request $request, ?int $clientId = null): void
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

        // Check if email already exists (exclude current client when editing)
        $existingEmailClient = Client::findByEmail($request->email);
        if ($existingEmailClient && (!$clientId || $existingEmailClient->id !== $clientId)) {
            $errors['email'] = 'Aquest email ja està registrat.';
        }

        // Check if username already exists (exclude current client when editing)
        $existingLoginClient = Client::findByNomLogin($request->nom_login);
        if ($existingLoginClient && (!$clientId || $existingLoginClient->id !== $clientId)) {
            $errors['nom_login'] = 'Aquest nom d\'usuari ja està en ús.';
        }

        // Password validation (required for new clients, optional for updates)
        if (!$clientId) { // New client
            if (empty($request->contrasena)) {
                $errors['contrasena'] = 'La contrasenya és obligatòria.';
            } elseif (strlen($request->contrasena) < 6) {
                $errors['contrasena'] = 'La contrasenya ha de tenir almenys 6 caràcters.';
            }
        } else { // Updating client
            if (!empty($request->contrasena) && strlen($request->contrasena) < 6) {
                $errors['contrasena'] = 'La contrasenya ha de tenir almenys 6 caràcters.';
            }
        }

        // Phone validation
        if ($tlf_valid && !preg_match('/^[0-9+\-\s()]+$/', $request->tlf)) {
            $errors['tlf'] = 'El format del telèfon no és vàlid.';
        }

        if ($errors) {
            session()->flash('errors', $errors);
            session()->flash('old', [
                'nom' => $request->nom,
                'cognom' => $request->cognom,
                'email' => $request->email,
                'tlf' => $request->tlf,
                'consulta' => $request->consulta,
                'missatge' => $request->missatge,
                'nom_login' => $request->nom_login,
            ]);
        
            if ($clientId) {
                redirect('/clients/edit.php?id=' . $clientId)->send();
            } else {
                redirect('/clients/create.php')->send();
            }
        }
    }
}
