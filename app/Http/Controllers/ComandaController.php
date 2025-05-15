<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Auth;
use App\Models\Client;
use App\Models\Comanda;

class ComandaController
{
    // Proceso de pedido desde el carrito (usuario autenticado)
    public function processOrder(Request $request)
    {
        if (!Auth::check()) {
            redirect('/auth/show-login.php')->with('error', 'Debes iniciar sesión para realizar un pedido')->send();
        }

        $userId = Auth::id();
        $client = Client::findOrFail($userId);

        // Crear la comanda
        $comanda = new Comanda();
        $comanda->client_id = $userId;
        $comanda->data_comanda = date('Y-m-d H:i:s');
        $comanda->direccio_enviament = $request->address ?? $request->direccio_enviament;
        $comanda->total = (float)($request->cart_total ?? $request->total);
        $comanda->estat = 'Pendent';
        $comanda->save();

        // Actualizar notas del cliente si hay
        if (!empty($request->notes)) {
            $currentNotes = $client->missatge ?? '';
            $client->missatge = $currentNotes . '| ' . $request->notes;
            $client->save();
        }

        redirect('/index.php')->with('success', 'Comanda processada amb èxit')->send();
    }

    // Formulario de carrito/pedido (usuario autenticado)
    public function showForm()
    {
        if (!Auth::check()) {
            redirect('/auth/show-login.php')->with('error', 'Debes iniciar sesión para realizar un pedido')->send();
        }

        view('cart.form');
    }

    // CRUD administrativo de comandes

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
        $comanda->client_id = $request->client_id ?? Auth::id();
        $comanda->total = $request->total;
        $comanda->estat = $request->estat ?? 'Pendent';
        $comanda->direccio_enviament = $request->direccio_enviament;
        $comanda->data_comanda = $request->data_comanda ?? date('Y-m-d H:i:s');
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
        $comanda->data_comanda = $request->data_comanda ?? $comanda->data_comanda;
        $comanda->save();

        redirect('/comandes/index.php')->with('success', 'Comanda actualitzada amb èxit')->send();
    }

    public function destroy(string $id)
    {
        $comanda = Comanda::findOrFail($id);
        $comanda->delete();
        redirect('/comandes/index.php')->with('success', 'Comanda eliminada amb èxit')->send();
    }

    // Pedidos del usuario autenticado
    public function myOrders()
    {
        $comandes = Comanda::where('client_id', Auth::id())
            ->orderBy('data_comanda', 'DESC')
            ->get();

        view('comandes.my-orders', compact('comandes'));
    }
}
