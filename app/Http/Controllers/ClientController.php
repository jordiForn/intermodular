<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../Models/Client.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Models/Comanda.php';

use App\Core\Request;
use App\Models\Client;
use App\Models\User;
use App\Models\Comanda;

class ClientController
{
    public function index()
    {
        $clients = Client::all();
        view('clients.index', compact('clients'));
    }

    public function show(string $id)
    {
        $client = Client::findOrFail($id);
        view('clients.show', compact('client'));
    }

    public function create()
    {
        view('clients.create');
    }

    public function store(Request $request)
    {
        // Create user first
        $user = new User();
        $user->username = $request->nom_login;
        $user->email = $request->email;
        $user->password = password_hash($request->contrasena, PASSWORD_DEFAULT);
        $user->role = 'user';
        $user->save();
        
        // Then create client
        $client = new Client();
        $client->user_id = $user->id;
        $client->nom = $request->nom;
        $client->cognom = $request->cognom;
        $client->tlf = $request->tlf;
        $client->consulta = $request->consulta;
        $client->missatge = $request->missatge;
        $client->save();

        redirect('/clients/index.php')->with('success', 'Client inserit amb èxit')->send();
    }

    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        $user = $client->user();
        view('clients.edit', compact('client', 'user'));
    }

    public function update(Request $request)
    {
        $client = Client::findOrFail($request->id);
        $user = $client->user();
        
        // Update user data
        $user->username = $request->nom_login;
        $user->email = $request->email;
        
        if (!empty($request->contrasena)) {
            $user->password = password_hash($request->contrasena, PASSWORD_DEFAULT);
        }
        
        $user->save();
        
        // Update client data
        $client->nom = $request->nom;
        $client->cognom = $request->cognom;
        $client->tlf = $request->tlf;
        $client->consulta = $request->consulta;
        $client->missatge = $request->missatge;
        $client->save();
        
        redirect('/clients/index.php')->with('success', 'Client modificat amb èxit')->send();
    }

    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $comandes = $client->comandes;
        
        if(!empty($comandes)){
            back()->with('error', 'No es pot eliminar un client que té comandes.')->send();
        }
        
        // Get the user associated with this client
        $user = $client->user();
        
        // Delete the client first (due to foreign key constraints)
        $client->delete();
        
        // Then delete the user if it exists
        if ($user) {
            $user->delete();
        }
        
        redirect('/clients/index.php')->with('success', 'Client eliminat amb èxit')->send();
    }
}
