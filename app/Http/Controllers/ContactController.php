<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Auth;
use App\Models\Client;
use App\Http\Middlewares\Middleware;

class ContactController {
    public function showForm()
    {
        view('contact.form');
    }
    
    public function store(Request $request)
    {
        
        // Validate request
        $errors = [];
        if (strlen(trim($request->consulta)) === 0) {
        $errors['consulta'] = 'La consulta és obligatòria.';
        }
        
        // If user is not authenticated, validate name and email
        if (!Auth::check()) {
            if (empty($request->name)) {
                $errors['name'] = 'El nom és obligatori.';
            }
            
            if (empty($request->email)) {
                $errors['email'] = 'L\'email és obligatori.';
            } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'L\'email no és vàlid.';
            }
        }
        
        if (!empty($errors)) {
            back()->withErrors($errors)->withInput([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'consulta' => $request->consulta
            ])->send();
        }
        
        // Process the contact form
        if (Auth::check()) {
            // Get the client
            $client = Auth::user(); // o Auth::client(); según tu sistema

    // Actualizar la consulta del cliente
    $currentConsulta = $client->consulta ?? '';
    $newConsulta = empty($currentConsulta)
        ? $request->consulta
        : $currentConsulta . ' | ' . $request->consulta;

    $client->consulta = $newConsulta;
    $client->save();
        } else {
            // Create a new client record or handle guest messages
            // This could be implemented based on business requirements
            // For now, we'll just store the message in a session for demonstration
            session()->flash('guest_message', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->consulta,
                'date' => date('Y-m-d H:i:s')
            ]);
            
            // In a real implementation, you might:
            // 1. Send an email to the admin
            // 2. Store in a messages table
            // 3. Create a new client record
        }
        
        redirect('/index.php')->with('success', 'Contacte enviat amb èxit. Ens posarem en contacte amb tu el més aviat possible.')->send();
    }
}
