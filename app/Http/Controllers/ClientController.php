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
        
        try {
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
            $client->consulta = $request->consulta ?? null;
            $client->missatge = $request->missatge ?? null;
            $client->rol = (int)($request->rol ?? 0);
            
            if ($client->insert()) {
                // Redirect to clients index with success message
                session()->flash('success', 'Client creat correctament.');
                redirect('/admin/clients.php')->send();
            } else {
                // Handle insertion error
                session()->flash('error', 'Error en crear el client. Torna-ho a intentar.');
                session()->flash('old', [
                    'nom' => $request->nom,
                    'cognom' => $request->cognom,
                    'email' => $request->email,
                    'tlf' => $request->tlf,
                    'nom_login' => $request->nom_login,
                    'consulta' => $request->consulta,
                    'missatge' => $request->missatge
                ]);
                redirect('/clients/create.php')->send();
            }
        } catch (Exception $e) {
            // Handle any other errors
            session()->flash('error', 'Error inesperat: ' . $e->getMessage());
            session()->flash('old', [
                'nom' => $request->nom,
                'cognom' => $request->cognom,
                'email' => $request->email,
                'tlf' => $request->tlf,
                'nom_login' => $request->nom_login,
                'consulta' => $request->consulta,
                'missatge' => $request->missatge
            ]);
            redirect('/clients/create.php')->send();
        }
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
        
        try {
            // Validate request
            $validator = new \App\Http\Validators\ClientValidator();
            $validator->validate($request, $id);
        
            // Update client
            $client->nom = $request->nom;
            $client->cognom = $request->cognom ?? '';
            $client->email = $request->email;
            $client->tlf = $request->tlf ?? '';
            $client->consulta = $request->consulta ?? null;
            $client->missatge = $request->missatge ?? null;
        
            // Only admin can update login credentials and role
            if (Auth::user()->role === 'admin') {
                $client->nom_login = $request->nom_login;
                $client->rol = (int)($request->rol ?? $client->rol);
            
                if (!empty($request->contrasena)) {
                    $client->contrasena = password_hash($request->contrasena, PASSWORD_DEFAULT);
                }
            }
        
            if ($client->update()) {
                // Redirect back with success message
                session()->flash('success', 'Client actualitzat correctament.');
            
                if (Auth::user()->role === 'admin') {
                    redirect('/admin/clients.php')->send();
                } else {
                    redirect('/profile')->send();
                }
            } else {
                // Handle update error
                session()->flash('error', 'Error en actualitzar el client. Torna-ho a intentar.');
                session()->flash('old', [
                    'nom' => $request->nom,
                    'cognom' => $request->cognom,
                    'email' => $request->email,
                    'tlf' => $request->tlf,
                    'nom_login' => $request->nom_login,
                    'consulta' => $request->consulta,
                    'missatge' => $request->missatge
                ]);
                redirect('/clients/edit.php?id=' . $id)->send();
            }
        } catch (Exception $e) {
            // Handle any other errors
            session()->flash('error', 'Error inesperat: ' . $e->getMessage());
            session()->flash('old', [
                'nom' => $request->nom,
                'cognom' => $request->cognom,
                'email' => $request->email,
                'tlf' => $request->tlf,
                'nom_login' => $request->nom_login,
                'consulta' => $request->consulta,
                'missatge' => $request->missatge
            ]);
            redirect('/clients/edit.php?id=' . $id)->send();
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
            session()->flash('error', 'Client no trobat.');
            redirect('/admin/clients.php')->send();
            return;
        }
        
        try {
            // Prevent deleting the current admin user
            if ($client->id === Auth::user()->client()->id) {
                session()->flash('error', 'No pots eliminar el teu propi compte.');
                redirect('/admin/clients.php')->send();
                return;
            }
            
            $clientName = $client->nom . ' ' . $client->cognom;
            
            if ($client->delete()) {
                // Redirect back with success message
                session()->flash('success', "Client '{$clientName}' eliminat correctament.");
            } else {
                session()->flash('error', 'Error en eliminar el client. Torna-ho a intentar.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Error inesperat: ' . $e->getMessage());
        }
        
        redirect('/admin/clients.php')->send();
    }
}
