<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Models\Comanda;
use App\Models\Producte;

class ComandaController
{
    public function index(): void
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        $comandes = Comanda::all();
        view('comandes.index', ['comandes' => $comandes]);
    }

    public function myOrders(): void
    {
        // Check if user is logged in
        if (!Auth::check()) {
            session()->put('intended_url', '/comandes/my-orders');
            session()->flash('error', 'Has d\'iniciar sessió per veure les teves comandes.');
            redirect('/auth/show-login.php')->send();
            return;
        }
        
        $client = Auth::user()->client();
        
        if (!$client) {
            http_error(404, 'Client no trobat.');
            return;
        }
        
        $comandes = $client->comandes()->get();
        view('comandes.my-orders', ['comandes' => $comandes]);
    }
    
    public function show(int $id): void
    {
        // Check if user is admin or the order owner
        if (!Auth::check()) {
            session()->put('intended_url', '/comandes/show.php?id=' . $id);
            session()->flash('error', 'Has d\'iniciar sessió per veure aquesta comanda.');
            redirect('/auth/show-login.php')->send();
            return;
        }
        
        $comanda = Comanda::find($id);
        
        if (!$comanda) {
            http_error(404, 'Comanda no trobada.');
            return;
        }
        
        // Check if user is admin or the order owner
        if (Auth::user()->role !== 'admin' && Auth::user()->client()->id !== $comanda->client_id) {
            http_error(403, 'No tens permís per accedir a aquesta comanda.');
            return;
        }
        
        view('comandes.show', ['comanda' => $comanda]);
    }
    
    public function create(): void
    {
        // Check if user is logged in
        if (!Auth::check()) {
            session()->put('intended_url', '/comandes/create');
            session()->flash('error', 'Has d\'iniciar sessió per crear una comanda.');
            redirect('/auth/show-login.php')->send();
            return;
        }
        
        view('comandes.create');
    }
    
    public function store(Request $request): void
    {
        // Check if user is logged in
        if (!Auth::check()) {
            session()->put('intended_url', '/comandes/create');
            session()->flash('error', 'Has d\'iniciar sessió per crear una comanda.');
            redirect('/auth/show-login.php')->send();
            return;
        }
        
        // Validate request
        $validator = new \App\Http\Validators\ComandaValidator();
        $validator->validate($request);
        
        // Create order
        $comanda = new Comanda();
        $comanda->client_id = Auth::user()->client()->id;
        $comanda->data_comanda = date('Y-m-d H:i:s');
        $comanda->total = $request->total;
        $comanda->estat = 'Pendent';
        $comanda->direccio_enviament = $request->direccio_enviament;
        $comanda->insert();
        
        // Redirect to order confirmation
        session()->flash('success', 'Comanda creada correctament.');
        redirect('/comandes/show.php?id=' . $comanda->id)->send();
    }
    
    public function edit(int $id): void
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        $comanda = Comanda::find($id);
        
        if (!$comanda) {
            http_error(404, 'Comanda no trobada.');
            return;
        }
        
        view('comandes.edit', ['comanda' => $comanda]);
    }
    
    public function update(Request $request, int $id): void
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        $comanda = Comanda::find($id);
        
        if (!$comanda) {
            http_error(404, 'Comanda no trobada.');
            return;
        }
        
        // Validate request
        $validator = new \App\Http\Validators\ComandaValidator();
        $validator->validate($request);
        
        // Update order
        $comanda->client_id = $request->client_id;
        $comanda->total = $request->total;
        $comanda->estat = $request->estat;
        $comanda->direccio_enviament = $request->direccio_enviament;
        $comanda->update();
        
        // Redirect back with success message
        session()->flash('success', 'Comanda actualitzada correctament.');
        redirect('/comandes')->send();
    }
    
    public function destroy(int $id): void
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            http_error(403, 'No tens permís per accedir a aquesta pàgina.');
            return;
        }
        
        $comanda = Comanda::find($id);
        
        if (!$comanda) {
            http_error(404, 'Comanda no trobada.');
            return;
        }
        
        $comanda->delete();
        
        // Redirect back with success message
        session()->flash('success', 'Comanda eliminada correctament.');
        redirect('/comandes')->send();
    }
    
    public function cart(): void
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $items = [];
        
        foreach ($cart as $productId => $quantity) {
            $product = Producte::find($productId);
            
            if ($product) {
                $subtotal = $product->preu * $quantity;
                $total += $subtotal;
                
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            }
        }
        
        view('comandes.cart', [
            'items' => $items,
            'total' => $total
        ]);
    }
    
    public function addToCart(Request $request): void
    {
        $productId = (int) $request->product_id;
        $quantity = (int) ($request->quantity ?? 1);
        
        if ($quantity <= 0) {
            $quantity = 1;
        }
        
        $product = Producte::find($productId);
        
        if (!$product) {
            session()->flash('error', 'Producte no trobat.');
            back()->send();
            return;
        }
        
        // Check if product is in stock
        if ($product->estoc < $quantity) {
            session()->flash('error', 'No hi ha suficient estoc disponible.');
            back()->send();
            return;
        }
        
        // Add to cart
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }
        
        session()->put('cart', $cart);
        
        // Redirect back with success message
        session()->flash('success', 'Producte afegit al carret.');
        back()->send();
    }
    
    public function updateCart(Request $request): void
    {
        $cart = [];
        
        foreach ($request->quantity as $productId => $quantity) {
            if ($quantity > 0) {
                $cart[$productId] = (int) $quantity;
            }
        }
        
        session()->put('cart', $cart);
        
        // Redirect back with success message
        session()->flash('success', 'Carret actualitzat.');
        redirect('/comandes/cart.php')->send();
    }
    
    public function checkout(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Check if user is logged in
        if (!Auth::check()) {
            session()->put('intended_url', '/comandes/checkout.php');
            session()->flash('error', 'Has d\'iniciar sessió per finalitzar la compra.');
            redirect('/auth/show-login.php')->send();
            return;
        }
        
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            session()->flash('error', 'El carret està buit.');
            redirect('/comandes/cart.php')->send();
            return;
        }
        
        $total = 0;
        $items = [];
        
        foreach ($cart as $productId => $quantity) {
            $product = Producte::find($productId);
            
            if ($product) {
                $subtotal = $product->preu * $quantity;
                $total += $subtotal;
                
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            }
        }
        
        $client = Auth::user()->client();
        
        view('comandes.checkout-form', [
            'items' => $items,
            'total' => $total,
            'client' => $client
        ]);
    }
    
    public function processOrder(Request $request): void
{
    // Check if user is logged in
    if (!Auth::check()) {
        session()->put('intended_url', '/comandes/checkout.php');
        session()->flash('error', 'Has d\'iniciar sessió per finalitzar la compra.');
        redirect('/auth/show-login.php')->send();
        return;
    }

    $cart = session()->get('cart', []);

    if (empty($cart)) {
        session()->flash('error', 'El carret està buit.');
        redirect('/comandes/cart.php')->send();
        return;
    }

    // Calculate total
    $total = 0;
    foreach ($cart as $productId => $quantity) {
        $product = Producte::find($productId);
        if ($product) {
            $total += $product->preu * $quantity;
        }
    }

    // Concatenar missatge del client
    $client = Auth::user()->client();
    $nouMissatge = trim($request->missatge ?? '');
    $missatgeAnterior = $client->missatge ?? '';

    if ($nouMissatge !== '') {
        if ($missatgeAnterior !== '') {
            $missatgeFinal = $missatgeAnterior . ' | ' . $nouMissatge;
        } else {
            $missatgeFinal = $nouMissatge;
        }
        $client->missatge = $missatgeFinal;
        $client->save();
    }

    // Crear comanda
    $comanda = new Comanda();
    $comanda->client_id = $client->id;
    $comanda->data_comanda = date('Y-m-d H:i:s');
    $comanda->total = $total;
    $comanda->estat = 'Pendent';
    $comanda->direccio_enviament = $request->direccio_enviament;
    // Si quieres guardar el missatge también en la comanda, añade:
    // $comanda->missatge = $nouMissatge;
    $comanda->insert();

    // Clear cart
    session()->forget('cart');

    // Redirect to confirmation
    redirect('/comandes/confirmation.php?id=' . $comanda->id)->send();
}
    
    public function confirmation(int $id): void
    {
        // Check if user is logged in
        if (!Auth::check()) {
            session()->put('intended_url', '/comandes/confirmation.php?id=' . $id);
            session()->flash('error', 'Has d\'iniciar sessió per veure aquesta pàgina.');
            redirect('/auth/show-login.php')->send();
            return;
        }
        
        $comanda = Comanda::find($id);
        
        if (!$comanda) {
            http_error(404, 'Comanda no trobada.');
            return;
        }
        
        // Check if user is admin or the order owner
        if (Auth::user()->role !== 'admin' && Auth::user()->client()->id !== $comanda->client_id) {
            http_error(403, 'No tens permís per accedir a aquesta comanda.');
            return;
        }
        
        view('comandes.confirmation', ['comanda' => $comanda]);
    }
}
