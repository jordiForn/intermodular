<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Models\Client;
use App\Models\ClientDades;
use App\Models\Comanda;
use App\Http\Middlewares\Middleware;
use App\Core\Auth;

class ClientController
{
    // Listado de clientes (solo admin)
    public function index()
    {
        Middleware::role(['1']); // Solo admin

        $clients = Client::all();
        $clientsData = [];

        foreach ($clients as $client) {
            // Si existe ClientDades y método dades(), úsalo
            $dades = method_exists($client, 'dades') ? $client->dades() : null;
            $clientsData[] = [
                'id' => $client->id,
                'nom' => $dades ? $dades->nom : $client->nom,
                'cognom' => $dades ? $dades->cognom : $client->cognom,
                'email' => $dades ? $dades->email : $client->email,
                'tlf' => $dades ? $dades->tlf : $client->tlf,
                'rol' => $dades ? $dades->rol : $client->rol,
                'nom_login' => $client->nom_login ?? '',
            ];
        }

        view('clients.index', compact('clientsData'));
    }

    // Mostrar un cliente (solo admin)
    public function show(string $id)
    {
        Middleware::role(['1']); // Solo admin
        $client = Client::findOrFail($id);
        view('clients.show', compact('client'));
    }

    // Formulario de creación (solo admin)
    public function create()
    {
        Middleware::role(['1']); // Solo admin
        view('clients.create');
    }

    // Guardar nuevo cliente (solo admin)
    public function store(Request $request)
    {
        Middleware::role(['1']); // Solo admin

        $client = new Client();
        $client->nom = $request->nom;
        $client->cognom = $request->cognom;
        $client->email = $request->email;
        $client->tlf = $request->tlf;
        $client->consulta = $request->consulta;
        $client->missatge = $request->missatge;
        $client->nom_login = $request->nom_login;
        $client->contrasena = password_hash($request->contrasena, PASSWORD_DEFAULT);
        $client->rol = $request->rol ?? 'user';
        $client->save();

        redirect('/clients/index.php')->with('success', 'Client inserit amb èxit')->send();
    }

    // Formulario de edición (solo admin)
    public function edit(string $id)
    {
        Middleware::role(['1']); // Solo admin
        $client = Client::findOrFail($id);
        view('clients.edit', compact('client'));
    }

    // Actualizar cliente (solo admin)
    public function update(Request $request)
    {
        Middleware::role(['1']); // Solo admin

        // Si existe ClientDades y el usuario tiene datos extendidos, actualízalos
        $dades = class_exists(ClientDades::class) ? ClientDades::where('nom_login', $request->nom_login)->first() : null;
        if ($dades) {
            $dades->nom = $request->nom;
            $dades->cognom = $request->cognom;
            $dades->email = $request->email;
            $dades->tlf = $request->tlf ?? $request->telefon;
            $dades->rol = $request->rol;
            $dades->save();

            $client = Client::where('nom_login', $request->nom_login)->first();
            if ($client) {
                $client->rol = $request->rol;
                $client->save();
            }
        } else {
            // Si no hay datos extendidos, actualiza el cliente clásico
            $client = Client::findOrFail($request->id);
            $client->nom = $request->nom;
            $client->cognom = $request->cognom;
            $client->email = $request->email;
            $client->tlf = $request->tlf;
            $client->consulta = $request->consulta;
            $client->missatge = $request->missatge;
            $client->nom_login = $request->nom_login;
            if (!empty($request->contrasena)) {
                $client->contrasena = password_hash($request->contrasena, PASSWORD_DEFAULT);
            }
            $client->rol = $request->rol ?? $client->rol;
            $client->save();
        }

        redirect('/clients/index.php')->with('success', 'Client modificat amb èxit')->send();
    }

    // Eliminar cliente (solo admin, si no tiene comandes)
    public function destroy(string $id)
    {
        Middleware::role(['1']); // Solo admin

        $client = Client::findOrFail($id);
        $comandes = property_exists($client, 'comandes') ? $client->comandes : (method_exists($client, 'comandes') ? $client->comandes() : []);
        if (!empty($comandes)) {
            back()->with('error', 'No es pot eliminar un client que té comandes.')->send();
        }
        $client->delete();
        redirect('/clients/index.php')->with('success', 'Client eliminat amb èxit')->send();
    }

    // Formulario de contacto (usuario autenticado)
    public function contact()
    {
        Middleware::auth();

        $username = Auth::user()['username'] ?? null;
        $client = $username ? Client::findByUsername($username) : null;
        $dades = $client && method_exists($client, 'dades') ? $client->dades() : null;

        $data = [
            'username' => $username,
            'email' => $dades ? $dades->email : ($client->email ?? ''),
            'phone' => $dades ? $dades->tlf : ($client->tlf ?? '')
        ];

        view('clients.contact', $data);
    }

    // Guardar contacto (usuario autenticado)
    public function storeContact(Request $request)
    {
        Middleware::auth();

        $username = Auth::user()['username'] ?? null;
        $client = $username ? Client::findByUsername($username) : null;

        if ($client) {
            $notes = $request->notes;
            $currentNotes = $client->consulta ?? '';
            $client->consulta = $currentNotes . '| ' . $notes;
            $client->save();

            redirect('/index.php')->with('success', 'Contacte enviat amb èxit')->send();
        } else {
            back()->with('error', 'Error: Usuari no trobat')->send();
        }
    }
}

