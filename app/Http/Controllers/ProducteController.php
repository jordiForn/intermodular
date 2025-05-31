<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../Models/Producte.php';

use App\Core\Request;
use App\Core\Debug;
use App\Core\DB;
use App\Models\Producte;

class ProducteController {

   public function index(Request $request)
   {
       try {
           // Test database connection first
           $dbTest = DB::testConnection();
           if (!$dbTest['success']) {
               // Log the database connection error
               if (class_exists('\\App\\Core\\Debug')) {
                   Debug::log("Database connection error in ProducteController::index: " . $dbTest['message']);
               }
               
               // Show error view
               view('errors.database', ['message' => $dbTest['message']]);
               return;
           }
           
           $page = $request->page ?? 1;

           // Log the query we're about to execute
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Executing paginate query for products, page: $page");
           }

           $productes = Producte::orderBy('nom')->paginate(6, $page);
           $totalPages = ceil(Producte::count() / 6);
           
           // Log the results
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Query completed, found " . count($productes) . " products, total pages: $totalPages");
           }

           view('productes.index', compact('productes', 'page', 'totalPages'));
       } catch (\Throwable $e) {
           // Log the exception
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Exception in ProducteController::index: " . $e->getMessage());
               Debug::log("Stack trace: " . $e->getTraceAsString());
           }
           
           // Show error view
           view('errors.500', ['message' => $e->getMessage()]);
       }
   }
   
   public function search(Request $request)
   {
       try {
           $q = $request->q ?? '';
       
           if (empty($q)) {
               return redirect("/productes/index.php")->send();
           }

           $page = $request->page ?? 1;
           $segment = '%' . $q . '%';

           $totalResults = count(Producte::where('nom', 'LIKE', $segment)->get());
           $productes = Producte::where('nom', 'LIKE', $segment)
                                ->limit(3)
                                ->offset(($page - 1) * 3)
                                ->get();
       
           $totalPages = ceil($totalResults / 3);
       
           view('productes.search', compact('productes', 'q', 'page', 'totalPages'));
       } catch (\Throwable $e) {
           // Log the exception
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Exception in ProducteController::search: " . $e->getMessage());
               Debug::log("Stack trace: " . $e->getTraceAsString());
           }
           
           // Show error view
           view('errors.500', ['message' => $e->getMessage()]);
       }
   }

    public function create()
    {
        view('productes.create');
    }

    public function store(Request $request)
    {
        $producte = new Producte();
        $producte->nom = $request->nom;
        $producte->descripcio = $request->descripcio;
        $producte->preu = $request->preu;
        $producte->estoc = $request->estoc;
        $producte->categoria = $request->categoria;
        $producte->imatge = $request->imatge;
        $producte->detalls = $request->detalls;
        $producte->save();
        redirect('/productes/index.php')->with('success', 'Producte inserit amb èxit')->send();
    }

    public function show(string $id)
    {
        $producte = Producte::findOrFail($id);
        view('productes.show', compact('producte'));
    }

    public function edit(string $id)
    {
        $producte = Producte::findOrFail($id);
        view('productes.edit', compact('producte'));
    }

    public function update(Request $request)
    {
        $producte = Producte::findOrFail($request->id);
        $producte->nom = $request->nom;
        $producte->descripcio = $request->descripcio;
        $producte->preu = $request->preu;
        $producte->estoc = $request->estoc;
        $producte->categoria = $request->categoria;
        $producte->imatge = $request->imatge;
        $producte->detalls = $request->detalls;
        $producte->save();
        redirect('/productes/index.php')->with('success', 'Producte modificat amb èxit')->send();
    }

    public function destroy(string $id)
    {
        $producte = Producte::findOrFail($id);
        $producte->delete();
        redirect('/productes/index.php')->with('success', 'Producte eliminat amb èxit')->send();
    }

    public function byCategory(Request $request)
    {
        $categoria = $request->categoria ?? '';
        
        if (empty($categoria)) {
            return redirect("/productes/index.php")->send();
        }
        
        $page = $request->page ?? 1;
        $productes = Producte::where('categoria', $categoria)
                            ->paginate(6, $page);
        
        $totalPages = ceil(Producte::where('categoria', $categoria)->count() / 6);
        
        view('productes.category', compact('productes', 'categoria', 'page', 'totalPages'));
    }
    
    /**
     * Display a list of products for batch editing
     * 
     * @param Request $request The request object
     * @return void
     */
    public function editList(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = 10; // More products per page for batch editing
        
        $categoria = $request->categoria ?? '';
        $searchTerm = $request->search ?? '';
        $sortBy = $request->sort_by ?? 'nom';
        $sortOrder = $request->sort_order ?? 'ASC';
        
        // Start building the query
        $query = Producte::orderBy($sortBy, $sortOrder);
        
        // Apply category filter if provided
        if (!empty($categoria)) {
            $query = $query->where('categoria', $categoria);
        }
        
        // Apply search filter if provided
        if (!empty($searchTerm)) {
            $query = $query->where('nom', 'LIKE', "%$searchTerm%");
        }
        
        // Get paginated results
        $productes = $query->paginate($perPage, $page);
        $totalPages = ceil($query->count() / $perPage);
        
        // Get unique categories for the filter dropdown
        $categories = [];
        $allProducts = Producte::all();
        foreach ($allProducts as $product) {
            if (!empty($product->categoria) && !in_array($product->categoria, $categories)) {
                $categories[] = $product->categoria;
            }
        }
        sort($categories);
        
        view('productes.edit-list', compact(
            'productes', 
            'page', 
            'totalPages', 
            'categories', 
            'categoria', 
            'searchTerm', 
            'sortBy', 
            'sortOrder'
        ));
    }
    
    /**
     * Update multiple products at once
     * 
     * @param Request $request The request object
     * @return void
     */
    public function updateBatch(Request $request)
    {
        $productIds = $request->product_ids ?? [];
        $updates = $request->updates ?? [];
        
        if (empty($productIds) || empty($updates)) {
            redirect('/productes/edit-list.php')
                ->with('error', 'No s\'han seleccionat productes per actualitzar')
                ->send();
        }
        
        $updatedCount = 0;
        
        foreach ($productIds as $id) {
            $producte = Producte::find($id);
            if (!$producte) continue;
            
            $updated = false;
            
            // Only update fields that are included in the updates array
            if (isset($updates['categoria']) && !empty($updates['categoria'])) {
                $producte->categoria = $updates['categoria'];
                $updated = true;
            }
            
            if (isset($updates['estoc_adjust']) && is_numeric($updates['estoc_adjust'])) {
                $adjustment = (int)$updates['estoc_adjust'];
                $producte->estoc = max(0, $producte->estoc + $adjustment);
                $updated = true;
            }
            
            if (isset($updates['preu_adjust']) && is_numeric($updates['preu_adjust'])) {
                $adjustment = (float)$updates['preu_adjust'];
                if (isset($updates['preu_adjust_type']) && $updates['preu_adjust_type'] === 'percent') {
                    // Percentage adjustment
                    $producte->preu = $producte->preu * (1 + ($adjustment / 100));
                } else {
                    // Absolute adjustment
                    $producte->preu = max(0, $producte->preu + $adjustment);
                }
                $updated = true;
            }
            
            if ($updated) {
                $producte->save();
                $updatedCount++;
            }
        }
        
        redirect('/productes/edit-list.php')
            ->with('success', "S'han actualitzat $updatedCount productes amb èxit")
            ->send();
    }

/**
 * Get product details via AJAX for inline editing
 * 
 * @param Request $request The request object containing the product ID
 * @return void
 */
public function getProductDetails(Request $request)
{
    try {
        $id = $request->id;
        $producte = Producte::findOrFail($id);
        
        // Return JSON response with product details
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'product' => [
                'id' => $producte->id,
                'nom' => $producte->nom,
                'descripcio' => $producte->descripcio,
                'preu' => $producte->preu,
                'estoc' => $producte->estoc,
                'categoria' => $producte->categoria,
                'imatge' => $producte->imatge,
                'detalls' => $producte->detalls
            ]
        ]);
        exit;
    } catch (\Throwable $e) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error al obtenir els detalls del producte: ' . $e->getMessage()
        ]);
        exit;
    }
}

/**
 * Update a single product via AJAX
 * 
 * @param Request $request The request object containing the product data
 * @return void
 */
public function quickUpdate(Request $request)
{
    try {
        // Validate CSRF token
        if (empty($request->csrf_token) || $request->csrf_token !== session()->get('csrf_token')) {
            throw new \Exception('Token CSRF no vàlid');
        }
        
        $id = $request->id;
        $producte = Producte::findOrFail($id);
        
        // Update only the fields that were provided
        if (isset($request->nom)) {
            $producte->nom = $request->nom;
        }
        
        if (isset($request->descripcio)) {
            $producte->descripcio = $request->descripcio;
        }
        
        if (isset($request->preu) && is_numeric($request->preu)) {
            $producte->preu = (float)$request->preu;
        }
        
        if (isset($request->estoc) && is_numeric($request->estoc)) {
            $producte->estoc = (int)$request->estoc;
        }
        
        if (isset($request->categoria)) {
            $producte->categoria = $request->categoria;
        }
        
        // Save the changes
        $producte->save();
        
        // Return success response
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Producte actualitzat correctament',
            'product' => [
                'id' => $producte->id,
                'nom' => $producte->nom,
                'descripcio' => $producte->descripcio,
                'preu' => $producte->preu,
                'estoc' => $producte->estoc,
                'categoria' => $producte->categoria
            ]
        ]);
        exit;
    } catch (\Throwable $e) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualitzar el producte: ' . $e->getMessage()
        ]);
        exit;
    }
}
}
