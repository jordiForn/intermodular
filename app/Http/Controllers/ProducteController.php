<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Models\Producte;
use App\Http\Middlewares\Middleware;

class ProducteController
{
    // Listado principal organizado por categorías
    public function index(Request $request = null)
    {
        // Si hay paginación, mostrar productos paginados
        if ($request && isset($request->page)) {
            $page = $request->page ?? 1;
            $productes = Producte::orderBy('nom')->paginate(6, $page);
            $totalPages = ceil(Producte::count() / 6);
            view('productes.index', compact('productes', 'page', 'totalPages'));
            return;
        }

        // Por defecto, organizar por categorías
        $productes = Producte::getAvailable();
        $categories = [];
        foreach ($productes as $producte) {
            $categoria = $producte->categoria;
            $categories[$categoria][] = $producte;
        }
        view('productes.index', compact('categories'));
    }

    // Búsqueda de productos
    public function search(Request $request)
    {
        $q = $request->q ?? '';
        if (empty($q)) {
            return redirect("/productes/index.php")->send();
        }

        $page = $request->page ?? 1;
        $segment = '%' . $q . '%';

        $totalResults = Producte::where('nom', 'LIKE', $segment)->count();
        $productes = Producte::where('nom', 'LIKE', $segment)
                             ->limit(3)
                             ->offset(($page - 1) * 3)
                             ->get();

        $totalPages = ceil($totalResults / 3);

        view('productes.search', compact('productes', 'q', 'page', 'totalPages'));
    }

    // Mostrar un producto
    public function show(string $id)
    {
        $producte = Producte::findOrFail((int)$id);
        view('productes.show', compact('producte'));
    }

    // Formulario de creación (solo admin)
    public function create()
    {
        Middleware::role(['1']); // Solo admin
        $categories = Producte::getCategories();
        view('productes.create', compact('categories'));
    }

    // Guardar nuevo producto (solo admin)
    public function store(Request $request)
    {
        Middleware::role(['1']); // Solo admin

        $producte = new Producte();
        $producte->nom = $request->nom ?? $request->name;
        $producte->descripcio = $request->descripcio ?? $request->description;
        $producte->preu = isset($request->preu) ? (float)$request->preu : (float)$request->price;
        $producte->estoc = isset($request->estoc) ? (int)$request->estoc : (int)$request->stock;
        $producte->categoria = $request->categoria ?? $request->category;
        $producte->detalls = $request->detalls ?? null;

        // Manejo seguro de la imagen
        $file = $request->file('imatge') ?? $request->file('image');
        if ($file && $file['error'] === 0) {
            $fileName = time() . '_' . $file['name'];
            move_uploaded_file($file['tmp_name'], __DIR__ . '/../../../public/images/' . $fileName);
            $producte->imatge = $fileName;
        } else {
            $producte->imatge = 'default.jpg';
        }

        $producte->save();
        redirect('/productes/index.php')->with('success', 'Producte afegit amb èxit')->send();
    }

    // Formulario de edición (solo admin)
    public function edit(string $id)
    {
        Middleware::role(['1']); // Solo admin
        $producte = Producte::findOrFail((int)$id);
        $categories = Producte::getCategories();
        view('productes.edit', compact('producte', 'categories'));
    }

    // Actualizar producto (solo admin)
    public function update(Request $request)
    {
        Middleware::role(['1']); // Solo admin

        $producte = Producte::findOrFail((int)$request->id);
        $producte->nom = $request->nom;
        $producte->descripcio = $request->descripcio;
        $producte->preu = (float)$request->preu;
        $producte->estoc = (int)$request->estoc;
        $producte->categoria = $request->categoria;
        $producte->detalls = $request->detalls ?? $producte->detalls;

        // Manejo seguro de la imagen
        $file = $request->file('imatge');
        if ($file && $file['error'] === 0) {
            $fileName = time() . '_' . $file['name'];
            move_uploaded_file($file['tmp_name'], __DIR__ . '/../../../public/images/' . $fileName);
            $producte->imatge = $fileName;
        }

        $producte->save();
        redirect('/productes/index.php')->with('success', 'Producte actualitzat amb èxit')->send();
    }

    // Eliminar producto (solo admin)
    public function destroy(string $id)
    {
        Middleware::role(['1']); // Solo admin
        $producte = Producte::findOrFail((int)$id);
        $producte->delete();
        redirect('/productes/index.php')->with('success', 'Producte eliminat amb èxit')->send();
    }

    // Listado de productos para edición por categoría (solo admin)
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

    // Filtrar productos por categoría (paginado)
    public function byCategory(Request $request)
    {
        $categoria = $request->categoria ?? '';
        if (empty($categoria)) {
            return redirect("/productes/index.php")->send();
        }

        $page = $request->page ?? 1;
        $productes = Producte::where('categoria', $categoria)->paginate(6, $page);
        $totalPages = ceil(Producte::where('categoria', $categoria)->count() / 6);

        view('productes.category', compact('productes', 'categoria', 'page', 'totalPages'));
    }
}
