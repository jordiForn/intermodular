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
        
        $userId = Auth::id();
        $client = Client::findOrFail($userId);
        
        // Validate input
        if (empty(trim($request->notes))) {
            back()->with('error', 'El missatge no pot estar buit')->send();
        }
        
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
