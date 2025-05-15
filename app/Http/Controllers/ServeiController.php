<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Models\Servei;

class ServeiController {
    public function index()
    {
        $serveis = Servei::all();
        view('serveis.index', compact('serveis'));
    }
    
    public function getJardins()
    {
        $serveis = Servei::getByCategory('jardins');
        view('serveis.category', [
            'serveis' => $serveis,
            'categoria' => 'jardins'
        ]);
    }
    
    public function getPiscines()
    {
        $serveis = Servei::getByCategory('piscines');
        view('serveis.category', [
            'serveis' => $serveis,
            'categoria' => 'piscines'
        ]);
    }
    
    public function show(string $id)
    {
        $servei = Servei::findOrFail($id);
        view('serveis.show', compact('servei'));
    }
}
