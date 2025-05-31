<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Models\Client;

class ClientController
{
    public function index(): void
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        $clients = Client::all();
        view('clients.index', ['clients' => $clients]);
    }
    
    public function show(int $id): void
    {
        // Check if user is admin or the client owner
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->client()->id !== $id)) {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        $client = Client::find($id);
        
        if (!$client) {
            http_error(404, 'Client no trobat.');
            return;
        }
        
        view('clients.show', ['client' => $client]);
    }
    
    public function create(): void
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        view('clients.create');
    }
    
    public function store(Request $request): void
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        // Validate request
        $validator = new \App\Http\Validators\ClientValidator();
        $validator->validate($request);
        
        // Create client
        $client = new Client();
        $client->nom = $request->nom;
        $client->cognom = $request->cognom ?? '';
        $client->email = $request->email;
        $client->tlf = $request->tlf ?? '';
        $client->nom_login = $request->nom_login;
        $client->contrasena = password_hash($request->contrasena, PASSWORD_DEFAULT);
        $client->insert();
        
        // Redirect to clients index
        session()->flash('success', 'Client creat correctament.');
        redirect('/clients')->send();
    }
    
    public function edit(int $id): void
    {
        // Check if user is admin or the client owner
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->client()->id !== $id)) {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        $client = Client::find($id);
        
        if (!$client) {
            http_error(404, 'Client no trobat.');
            return;
        }
        
        view('clients.edit', ['client' => $client]);
    }
    
    public function update(Request $request, int $id): void
    {
        // Check if user is admin or the client owner
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->client()->id !== $id)) {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        $client = Client::find($id);
        
        if (!$client) {
            http_error(404, 'Client no trobat.');
            return;
        }
        
        // Validate request
        $validator = new \App\Http\Validators\ClientValidator();
        $validator->validate($request, $id);
        
        // Update client
        $client->nom = $request->nom;
        $client->cognom = $request->cognom ?? '';
        $client->email = $request->email;
        $client->tlf = $request->tlf ?? '';
        
        // Only admin can update login credentials
        if (Auth::user()->role === 'admin') {
            $client->nom_login = $request->nom_login;
            
            if (!empty($request->contrasena)) {
                $client->contrasena = password_hash($request->contrasena, PASSWORD_DEFAULT);
            }
        }
        
        $client->update();
        
        // Redirect back with success message
        session()->flash('success', 'Client actualitzat correctament.');
        
        if (Auth::user()->role === 'admin') {
            redirect('/clients')->send();
        } else {
            redirect('/profile')->send();
        }
    }
    
    public function destroy(int $id): void
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        $client = Client::find($id);
        
        if (!$client) {
            http_error(404, 'Client no trobat.');
            return;
        }
        
        $client->delete();
        
        // Redirect back with success message
        session()->flash('success', 'Client eliminat correctament.');
        redirect('/clients')->send();
    }
}
