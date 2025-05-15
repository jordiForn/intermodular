<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../Models/Comanda.php';
require_once __DIR__ . '/../../Models/Client.php';

use App\Core\Request;
use App\Core\Auth;
use App\Models\Comanda;
use App\Models\Client;

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
}
