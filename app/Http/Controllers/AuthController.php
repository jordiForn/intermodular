<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Auth;
use App\Models\Client;
use App\Models\ClientDades;

class AuthController {
    public function showLoginForm(): void
    {
        view('auth.login');
    }
    
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        
        $client = Client::where('nom_login', $username)->first();
        
        if ($client && $client->contrasena === $password) {
            session()->set('user', [
                'id' => $client->id,
                'username' => $client->nom_login,
                'email' => $client->email,
                'role' => $client->rol,
            ]);
            
            redirect('/index.php')->send();
        }
        
        back()->with('error', 'Credencials incorrectes')->send();
    }

    public function showRegisterForm(): void
    {
        view('auth.register');
    }

    public function register(Request $request)
    {
        // Crear el cliente
        $client = new Client();
        $client->nom = trim($request->name);
        $client->cognom = trim($request->surname);
        $client->email = trim($request->email);
        $client->tlf = trim($request->tlf);
        $client->nom_login = trim($request->username);
        $client->contrasena = $request->password;
        $client->rol = '0'; // Usuario normal por defecto
        $client->missatge = '';
        $client->consulta = '';
        $client->save();

        // Iniciar sesión
        session()->set('user', [
            'id' => $client->id,
            'username' => $client->nom_login,
            'email' => $client->email,
            'role' => $client->rol,
        ]);

        redirect('/index.php')->with('success', "¡Bienvenido, {$client->nom}!")->send();
    }

    public function logout(){
        Auth::logout();
        redirect('/index.php')->send();
    }
}
