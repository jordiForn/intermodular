<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Auth;
use App\Models\User;
use App\Models\Client;

class AuthController {

    public function showLoginForm(): void
    {
        view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'contrasena' => $request->contrasena,
        ];
    
        if (Auth::attempt($credentials)) {
            // Get redirect URL if it exists
            $redirectTo = session()->getFlash('redirect_to', '/productes/index.php');
            redirect($redirectTo)->with('success', 'Has iniciat sessió correctament')->send();
        }

        back()->with('error', 'Credencials incorrectes')->withInput([
            'email' => $request->email
        ])->send();
    }

    public function showRegisterForm(): void
    {
        view('auth.register');
    }

    public function register(Request $request)
    {
        // Create user
        $user = new User();
        $user->username = trim($request->nom_login);
        $user->email = trim($request->email);
        $user->password = password_hash($request->contrasena, PASSWORD_DEFAULT);
        $user->role = 'user';
        $user->save();

        // Create client profile
        $client = new Client();
        $client->user_id = $user->id;
        $client->nom = trim($request->nom);
        $client->cognom = trim($request->cognom ?? '');
        $client->tlf = trim($request->tlf ?? '');
        $client->save();

        // Log the user in
        session()->set('user', [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'client_id' => $client->id,
            'nom' => $client->nom,
        ]);

        redirect('/productes/index.php')->with('success', "¡Benvingut, {$client->nom}!")->send();
    }

    public function logout(){
        Auth::logout();
        redirect('/auth/show-login.php')->with('success', 'Has tancat la sessió correctament')->send();
    }
}
