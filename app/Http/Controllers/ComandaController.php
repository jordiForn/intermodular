<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Auth;
use App\Models\Client;
use App\Models\Comanda;

class ComandaController {
    public function processOrder(Request $request)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            redirect('/auth/show-login.php')->with('error', 'Debes iniciar sesión para realizar un pedido')->send();
        }
        
        $userId = Auth::id();
        $client = Client::findOrFail($userId);
        
        // Crear la comanda
        $comanda = new Comanda();
        $comanda->client_id = $userId;
        $comanda->data_comanda = date('Y-m-d H:i:s');
        $comanda->direccio_enviament = $request->address;
        $comanda->total = (float)$request->cart_total;
        $comanda->save();
        
        // Actualizar notas del cliente si hay
        if (!empty($request->notes)) {
            $currentNotes = $client->missatge ?? '';
            $client->missatge = $currentNotes . '| ' . $request->notes;
            $client->save();
        }
        
        redirect('/index.php')->with('success', 'Comanda processada amb èxit')->send();
    }
    
    public function showForm()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            redirect('/auth/show-login.php')->with('error', 'Debes iniciar sesión para realizar un pedido')->send();
        }
        
        view('cart.form');
    }
}
