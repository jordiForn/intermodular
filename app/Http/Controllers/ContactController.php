<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Auth;
use App\Models\Client;
use App\Http\Middlewares\Middleware;

class ContactController {
    public function showForm()
    {
        // Use middleware for authentication instead of direct check
        Middleware::auth();
        
        view('contact.form');
    }
    
    public function store(Request $request)
    {
        // Use middleware for authentication instead of direct check
        Middleware::auth();
        
        $clientId = Auth::clientId();
        $client = Client::findOrFail($clientId);
        
        // Validation is now handled by ContactValidator
        
        // Actualizar consulta del cliente
        $currentConsulta = $client->consulta ?? '';
        $newConsulta = empty($currentConsulta) ? 
            $request->notes : 
            $currentConsulta . ' | ' . $request->notes;
            
        $client->consulta = $newConsulta;
        $client->save();
        
        redirect('/index.php')->with('success', 'Contacte enviat amb èxit')->send();
    }
}
