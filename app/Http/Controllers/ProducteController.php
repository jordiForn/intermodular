<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Models\Producte;
use App\Http\Middlewares\Middleware;

class ProducteController {
    public function index()
    {
        $productes = Producte::getAvailable();
        
        // Organizar productos por categoría
        $categories = [];
        foreach ($productes as $producte) {
            $categoria = $producte->categoria;
            $categories[$categoria][] = $producte;
        }
        
        view('productes.index', compact('categories'));
    }

    public function show(string $id)
    {
        $producte = Producte::findOrFail((int)$id);
        view('productes.show', compact('producte'));
    }

    public function create()
    {
        Middleware::role(['1']); // Solo admin
        $categories = Producte::getCategories();
        view('productes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Middleware::role(['1']); // Solo admin
        
        $producte = new Producte();
        $producte->nom = $request->name;
        $producte->descripcio = $request->description;
        $producte->preu = (float)$request->price;
        $producte->estoc = (int)$request->stock;
        $producte->categoria = $request->category;
        
        // Manejo de la imagen
        if ($request->file('image') && $request->file('image')['error'] === 0) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file['name'];
            move_uploaded_file($file['tmp_name'], __DIR__ . '/../../../public/images/' . $fileName);
            $producte->imatge = $fileName;
        } else {
            $producte->imatge = 'default.jpg';
        }
        
        $producte->save();
        
        redirect('/productes/index.php')->with('success', 'Producte afegit amb èxit')->send();
    }

    public function edit(string $id)
    {
        Middleware::role(['1']); // Solo admin
        $producte = Producte::findOrFail((int)$id);
        $categories = Producte::getCategories();
        view('productes.edit', compact('producte', 'categories'));
    }

    public function update(Request $request)
    {
        Middleware::role(['1']); // Solo admin
        
        $producte = Producte::findOrFail((int)$request->id);
        $producte->nom = $request->nom;
        $producte->descripcio = $request->descripcio;
        $producte->preu = (float)$request->preu;
        $producte->estoc = (int)$request->estoc;
        $producte->categoria = $request->categoria;
        
        // Manejo de la imagen
        if ($request->file('imatge') && $request->file('imatge')['error'] === 0) {
            $file = $request->file('imatge');
            $fileName = time() . '_' . $file['name'];
            move_uploaded_file($file['tmp_name'], __DIR__ . '/../../../public/images/' . $fileName);
            $producte->imatge = $fileName;
        }
        
        $producte->save();
        
        redirect('/productes/index.php')->with('success', 'Producte actualitzat amb èxit')->send();
    }

    public function destroy(string $id)
    {
        Middleware::role(['1']); // Solo admin
        
        $producte = Producte::findOrFail((int)$id);
        $producte->delete();
        
        redirect('/productes/index.php')->with('success', 'Producte eliminat amb èxit')->send();
    }
    
    public function editList()
    {
        Middleware::role(['1']); // Solo admin
        
        $categories = Producte::getCategories();
        $category = request()->category ?? '';
        $products = [];
        
        if (!empty($category)) {
            $products = Producte::getByCategory($category);
        }
        
        view('productes.edit-list', compact('categories', 'products', 'category'));
    }
}
