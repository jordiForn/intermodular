<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../Models/Comanda.php';
require_once __DIR__ . '/../../Models/Client.php';
require_once __DIR__ . '/../../Models/Producte.php';

use App\Core\Request;
use App\Core\Auth;
use App\Models\Comanda;
use App\Models\Client;
use App\Models\Producte;

class ComandaController {

    public function index()
    {
        $comandes = Comanda::orderBy('data_comanda', 'DESC')->get();
        view('comandes.index', compact('comandes'));
    }

    public function create()
    {
        $clients = Client::orderBy('nom')->get();
        view('comandes.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $comanda = new Comanda();
        $comanda->client_id = Auth::clientId();
        $comanda->total = $request->total;
        $comanda->estat = 'Pendent';
        $comanda->direccio_enviament = $request->direccio_enviament;
        $comanda->save();
        
        redirect('/comandes/index.php')->with('success', 'Comanda creada amb èxit')->send();
    }

    public function show(string $id)
    {
        $comanda = Comanda::findOrFail($id);
        view('comandes.show', compact('comanda'));
    }

    public function edit(string $id)
    {
        $comanda = Comanda::findOrFail($id);
        $clients = Client::orderBy('nom')->get();
        view('comandes.edit', compact('comanda', 'clients'));
    }

    public function update(Request $request)
    {
        $comanda = Comanda::findOrFail($request->id);
        $comanda->client_id = $request->client_id;
        $comanda->total = $request->total;
        $comanda->estat = $request->estat;
        $comanda->direccio_enviament = $request->direccio_enviament;
        $comanda->save();
        
        redirect('/comandes/index.php')->with('success', 'Comanda actualitzada amb èxit')->send();
    }

    public function destroy(string $id)
    {
        $comanda = Comanda::findOrFail($id);
        $comanda->delete();
        redirect('/comandes/index.php')->with('success', 'Comanda eliminada amb èxit')->send();
    }

    public function myOrders()
    {
        $comandes = Comanda::where('client_id', Auth::clientId())
                          ->orderBy('data_comanda', 'DESC')
                          ->get();
        
        view('comandes.my-orders', compact('comandes'));
    }
    
    /**
     * Show the cart page
     * 
     * @return void
     */
    public function showCart()
    {
        view('comandes.cart');
    }
    
    /**
     * Show the checkout form
     * 
     * @return void
     */
    public function showCheckoutForm()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            redirect('/auth/show-login.php')
                ->with('error', 'Has d\'iniciar sessió per a realitzar una comanda')
                ->with('redirect_to', '/comandes/checkout.php')
                ->send();
        }
        
        $client = Client::findOrFail(Auth::clientId());
        view('comandes.checkout-form', compact('client'));
    }
    
    /**
     * Process an order from the cart
     * 
     * @param Request $request The request object
     * @return void
     */
    public function processOrder(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            redirect('/auth/show-login.php')
                ->with('error', 'Has d\'iniciar sessió per a realitzar una comanda')
                ->with('redirect_to', '/comandes/checkout.php')
                ->send();
        }
        
        // Validate request
        $errors = [];
        
        if (empty($request->cart_items)) {
            $errors['cart'] = 'El carret està buit';
        }
        
        if (empty($request->direccio_enviament)) {
            $errors['direccio_enviament'] = 'La direcció d\'enviament és obligatòria';
        }
        
        if (!empty($errors)) {
            back()->withErrors($errors)->withInput([
                'direccio_enviament' => $request->direccio_enviament,
                'notes' => $request->notes
            ])->send();
        }
        
        // Create the order
        $comanda = new Comanda();
        $comanda->client_id = Auth::clientId();
        $comanda->total = $request->total;
        $comanda->estat = 'Pendent';
        $comanda->direccio_enviament = $request->direccio_enviament;
        
        // Add notes if provided
        if (!empty($request->notes)) {
            $comanda->notes = $request->notes;
        }
        
        $comanda->save();
        
        // Process cart items
        $cartItems = json_decode($request->cart_items, true);
        if (is_array($cartItems)) {
            foreach ($cartItems as $item) {
                // Update product stock
                if (isset($item['id']) && isset($item['quantity'])) {
                    $producte = Producte::find($item['id']);
                    if ($producte) {
                        $producte->estoc = max(0, $producte->estoc - $item['quantity']);
                        $producte->save();
                    }
                }
            }
        }
        
        // Redirect to confirmation page
        redirect('/comandes/confirmation.php?id=' . $comanda->id)
            ->with('success', 'Comanda processada amb èxit')
            ->send();
    }
    
    /**
     * Show order confirmation page
     * 
     * @param string $id The order ID
     * @return void
     */
    public function showConfirmation(string $id)
    {
        $comanda = Comanda::findOrFail($id);
        
        // Ensure the user owns this order
        if (Auth::clientId() !== $comanda->client_id && Auth::role() !== 'admin') {
            redirect('/comandes/my-orders.php')
                ->with('error', 'No tens permís per a veure aquesta comanda')
                ->send();
        }
        
        view('comandes.confirmation', compact('comanda'));
    }
}
