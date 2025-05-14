<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Auth;
use App\Models\Client;

class ContactController {
    public function showForm()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            redirect('/auth/show-login.php')->with('error', 'Debes iniciar sesión para contactar')->send();
        }
        
        view('contact.form');
    }
    
    public function store(Request $request)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            redirect('/auth/show-login.php')->with('error', 'Debes iniciar sesión para contactar')->send();
        }
        
        $userId = Auth::id();
        $client = Client::findOrFail($userId);
        
        // Actualizar consulta del cliente
        $currentConsulta = $client->consulta ?? '';
        $client->consulta = $currentConsulta . '| ' . $request->notes;
        $client->save();
        
        redirect('/index.php')->with('success', 'Contacte enviat amb èxit')->send();
    }
}
