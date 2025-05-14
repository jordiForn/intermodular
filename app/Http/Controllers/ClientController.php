<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Models\Client;
use App\Models\ClientDades;
use App\Http\Middlewares\Middleware;

class ClientController {
    public function index()
    {
        Middleware::role(['1']); // Solo admin
        
        $clients = Client::all();
        $clientsData = [];
        
        foreach ($clients as $client) {
            $dades = $client->dades();
            if ($dades) {
                $clientsData[] = [
                    'id' => $client->id,
                    'nom' => $dades->nom,
                    'cognom' => $dades->cognom,
                    'email' => $dades->email,
                    'tlf' => $dades->tlf,
                    'rol' => $dades->rol,
                    'nom_login' => $client->nom_login
                ];
            }
        }
        
        view('clients.index', compact('clientsData'));
    }

    public function update(Request $request)
    {
        Middleware::role(['1']); // Solo admin
        
        $dades = ClientDades::where('nom_login', $request->nom_login)->first();
        if ($dades) {
            $dades->nom = $request->nom;
            $dades->cognom = $request->cognom;
            $dades->email = $request->email;
            $dades->tlf = $request->telefon;
            $dades->rol = $request->rol;
            $dades->save();
            
            $client = Client::where('nom_login', $request->nom_login)->first();
            if ($client) {
                $client->rol = $request->rol;
                $client->save();
            }
            
            echo "Usuari actualitzat correctament";
        } else {
            echo "Error: Usuari no trobat";
        }
    }

    public function destroy(string $id)
    {
        Middleware::role(['1']); // Solo admin
        
        $client = Client::findOrFail((int)$id);
        $client->delete();
        
        echo "Usuari eliminat correctament.";
    }
    
    public function contact()
    {
        Middleware::auth();
        
        $username = Auth::user()['username'];
        $client = Client::findByUsername($username);
        $dades = $client->dades();
        
        $data = [
            'username' => $username,
            'email' => $dades ? $dades->email : '',
            'phone' => $dades ? $dades->tlf : ''
        ];
        
        view('clients.contact', $data);
    }
    
    public function storeContact(Request $request)
    {
        Middleware::auth();
        
        $username = Auth::user()['username'];
        $client = Client::findByUsername($username);
        
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
