<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Auth;
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
            redirect('/productes/index.php')->send();
        }

        back()->with('error', 'Credencials incorrectes')->send();
    }

    public function showRegisterForm(): void
    {
        view('auth.register');
    }

    public function register(Request $request)
    {
        $client = new Client();
        $client->nom = trim($request->nom);
        $client->email = trim($request->email);
        $client->contrasena = password_hash($request->contrasena, PASSWORD_DEFAULT);
        $client->rol = 'user';
        $client->save();

        session()->set('user', [
            'id' => $client->id,
            'nom' => $client->nom,
            'email' => $client->email,
            'rol' => $client->rol,
        ]);

        redirect('/productes/index.php')->with('success', "¡Benvingut, $client->nom!")->send();
    }

    public function logout(){
        Auth::logout();
        redirect('/auth/show-login.php')->send();
    }
}
