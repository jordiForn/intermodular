<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../Models/Producte.php';

use App\Core\Request;
use App\Core\Debug;
use App\Core\DB;
use App\Models\Producte;
use App\Core\Auth;
use App\Http\Validators\ProducteValidator;

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

    public function create(Request $request = null)
    {
        Debug::log("ProducteController::create method called");
        
        // Ensure user is authenticated and has admin privileges
        if (!Auth::check() || !Auth::isAdmin()) {
            Debug::log("User not authenticated or not admin, redirecting to login");
            redirect('/auth/show-login.php?error=unauthorized')->send();
            return;
        }
        
        Debug::log("User authenticated as admin, proceeding with create view");
        
        try {
            // Initialize form data
            $producte = null;
            $fields = ['nom', 'descripcio', 'preu', 'estoc', 'categoria', 'imatge', 'detalls'];
            
            // Set the title for the admin layout
            $title = 'Crear Nou Producte - Intermodular Admin';
            
            Debug::log("About to render admin view for product creation");
            
            // Use the corrected renderAdminView method
            $this->renderAdminView('productes.create', compact('title', 'producte', 'fields'));
            
        } catch (\Throwable $e) {
            Debug::log("Exception in ProducteController::create: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            
            // Fallback to error view
            view('errors.500', ['message' => 'Error al carregar el formulari de creació: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        Debug::log("ProducteController::store method called");
        Debug::log("Raw request data: " . json_encode($_POST, JSON_UNESCAPED_UNICODE));
        
        // Ensure user is authenticated and has admin privileges
        if (!Auth::check() || !Auth::isAdmin()) {
            Debug::log("User not authenticated or not admin");
            redirect('/auth/show-login.php?error=unauthorized')->send();
            return;
        }
        
        try {
            // Log the available categories from database
            Debug::log("Available categories: " . json_encode(Producte::getCategories(), JSON_UNESCAPED_UNICODE));
            
            // Validate the request data first
            Debug::log("Starting validation");
            ProducteValidator::validate($request);
            Debug::log("Validation passed");
            
            // Prepare product data with exact database enum values
            $productData = [
                'nom' => trim($request->nom ?? ''),
                'descripcio' => trim($request->descripcio ?? ''),
                'preu' => (float)($request->preu ?? 0),
                'estoc' => (int)($request->estoc ?? 0),
                'categoria' => trim($request->categoria ?? ''),
                'imatge' => trim($request->imatge ?? ''),
                'detalls' => trim($request->detalls ?? '')
            ];
            
            Debug::log("Prepared product data: " . json_encode($productData, JSON_UNESCAPED_UNICODE));
            
            // Additional category validation with exact enum matching
            if (!Producte::isValidCategory($productData['categoria'])) {
                Debug::log("Invalid category provided: '" . $productData['categoria'] . "'");
                Debug::log("Valid categories are: " . json_encode(Producte::getCategories(), JSON_UNESCAPED_UNICODE));
                throw new \Exception("Categoria no vàlida: '" . $productData['categoria'] . "'. Categories vàlides: " . implode(', ', Producte::getCategories()));
            }
            
            // Create product using the factory method
            Debug::log("Creating product instance");
            $producte = Producte::createProduct($productData);
            
            // Log the product object before saving
            Debug::log("Product object before save: " . json_encode([
                'nom' => $producte->nom,
                'descripcio' => $producte->descripcio,
                'preu' => $producte->preu,
                'estoc' => $producte->estoc,
                'categoria' => $producte->categoria,
                'imatge' => $producte->imatge,
                'detalls' => $producte->detalls
            ], JSON_UNESCAPED_UNICODE));
        
            // Save the product
            Debug::log("Attempting to save product");
            $success = $producte->save();
            
            if (!$success) {
                throw new \Exception("Failed to save product to database");
            }
            
            Debug::log("Product saved successfully with ID: " . $producte->id);
            
            // Redirect with success message
            redirect('/admin/products.php')->with('success', 'Producte creat amb èxit')->send();
            
        } catch (\Throwable $e) {
            Debug::log("Exception in ProducteController::store: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            
            // Redirect back with error message
            redirect('/productes/create.php')
                ->with('error', 'Error al crear el producte: ' . $e->getMessage())
                ->withInput([
                    'nom' => $request->nom ?? '',
                    'descripcio' => $request->descripcio ?? '',
                    'preu' => $request->preu ?? '',
                    'estoc' => $request->estoc ?? '',
                    'categoria' => $request->categoria ?? '',
                    'imatge' => $request->imatge ?? '',
                    'detalls' => $request->detalls ?? ''
                ])
                ->send();
        }
    }

    public function show(string $id)
    {
        $producte = Producte::findOrFail($id);
        view('productes.show', compact('producte'));
    }

    public function edit(string $id)
    {
        // Ensure user is authenticated and has admin privileges
        if (!Auth::check() || !Auth::isAdmin()) {
            redirect('/auth/show-login.php?error=unauthorized')->send();
            return;
        }
        
        try {
            $producte = Producte::findOrFail($id);
            $title = 'Editar Producte - Intermodular Admin';
            
            // Render the edit view using the admin layout
            $this->renderAdminView('productes.edit', compact('title', 'producte'));
        } catch (\Throwable $e) {
            Debug::log("Exception in ProducteController::edit: " . $e->getMessage());
            redirect('/admin/products.php')
                ->with('error', 'Error al carregar el producte: ' . $e->getMessage())
                ->send();
        }
    }

    public function update(Request $request)
    {
        try {
            Debug::log("ProducteController::update called");
            // Ensure user is authenticated and has admin privileges
            if (!Auth::check() || !Auth::isAdmin()) {
                Debug::log("User not authenticated or not admin");
                redirect('/auth/show-login.php?error=unauthorized')->send();
                return;
            }
            
            // Find the product
            $producte = Producte::findOrFail($request->id);
            Debug::log("Found product: " . $producte->nom);
            
            // Validate the request data
            ProducteValidator::validate($request);
            
            // Update product fields
            $producte->nom = trim($request->nom);
            $producte->descripcio = trim($request->descripcio);
            $producte->preu = (float)$request->preu;
            $producte->estoc = (int)$request->estoc;
            $producte->categoria = trim($request->categoria);
            $producte->imatge = trim($request->imatge);
            $producte->detalls = trim($request->detalls);
            // Save the changes
            $success = $producte->save();
            if (!$success) {
                throw new \Exception("Failed to update product in database");
            }
            
            Debug::log("Product updated successfully");
            
            // Redirect to admin products page with success message
            redirect('/admin/products.php')
                ->with('success', 'Producte actualitzat amb èxit')
                ->send();
                
        } catch (\Throwable $e) {
            Debug::log("Exception in ProducteController::update: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            
            // Redirect back to edit page with error message
            redirect("/productes/edit.php?id=" . ($request->id ?? ''))
                ->with('error', 'Error al actualitzar el producte: ' . $e->getMessage())
                ->send();
        }
    }

    public function destroy(string $id)
    {
        try {
            Debug::log("ProducteController::destroy called with ID: $id");
        
            // Ensure user is authenticated and has admin privileges
            if (!Auth::check() || !Auth::isAdmin()) {
                Debug::log("User not authenticated or not admin");
                redirect('/auth/show-login.php?error=unauthorized')->send();
                return;
            }
        
            // Find the product
            $producte = Producte::findOrFail($id);
            Debug::log("Found product: " . $producte->nom);
        
            // Delete the product
            $success = $producte->delete();
        
            if (!$success) {
                throw new \Exception("Failed to delete product from database");
            }
        
            Debug::log("Product deleted successfully");
        
            // Redirect to admin products page with success message
            redirect('/admin/products.php')
                ->with('success', 'Producte eliminat amb èxit')
                ->send();
            
        } catch (\Throwable $e) {
            Debug::log("Exception in ProducteController::destroy: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
        
            // Redirect to admin products page with error message
            redirect('/admin/products.php')
                ->with('error', 'Error al eliminar el producte: ' . $e->getMessage())
                ->send();
        }
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
        $categories = Producte::getCategories();
        
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
                if (Producte::isValidCategory($updates['categoria'])) {
                    $producte->categoria = $updates['categoria'];
                    $updated = true;
                }
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
                if (Producte::isValidCategory($request->categoria)) {
                    $producte->categoria = $request->categoria;
                } else {
                    throw new \Exception('Categoria no vàlida');
                }
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
    
    /**
     * CORRECTED: Helper method to render views with admin layout
     * Fixed path resolution and improved error handling
     * 
     * @param string $viewName The view name (dot notation)
     * @param array $data Data to pass to the view
     * @return void
     */
    private function renderAdminView(string $viewName, array $data = [])
    {
        Debug::log("renderAdminView called with view: $viewName");
        
        try {
            // Extract data for use in the view
            extract($data);
            
            // Convert dot notation to file path
            $viewPath = str_replace('.', DIRECTORY_SEPARATOR, $viewName);
            
            // CORRECTED: Build the correct path to the view file
            // Go up 3 levels from app/Http/Controllers to reach the project root
            $projectRoot = dirname(__DIR__, 3);
            $basePath = $projectRoot . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
            $fullViewPath = $basePath . $viewPath . '.php';
            
            Debug::log("Project root: $projectRoot");
            Debug::log("Base path: $basePath");
            Debug::log("Looking for view file at: $fullViewPath");
            Debug::log("View path: $viewPath");
            
            // Check if the view file exists
            if (!file_exists($fullViewPath)) {
                Debug::log("View file not found: $fullViewPath");
                
                // Try to create the directory if it doesn't exist
                $viewDir = dirname($fullViewPath);
                if (!is_dir($viewDir)) {
                    Debug::log("Creating directory: $viewDir");
                    if (!mkdir($viewDir, 0755, true)) {
                        throw new \Exception("Failed to create directory: $viewDir");
                    }
                }
                
                throw new \Exception("View file not found: $fullViewPath. Please ensure the file exists.");
            }
            
            if (!is_readable($fullViewPath)) {
                Debug::log("View file not readable: $fullViewPath");
                throw new \Exception("View file not readable: $fullViewPath. Please check file permissions.");
            }
            
            Debug::log("View file found and readable, starting output buffering");
            
            // Start output buffering to capture the view content
            ob_start();
            
            // Include the specific view file
            include $fullViewPath;
            
            // Get the view content
            $content = ob_get_clean();
            
            Debug::log("View content captured, length: " . strlen($content));
            
            // Build path to admin layout
            $adminLayoutPath = $basePath . 'layouts' . DIRECTORY_SEPARATOR . 'admin.php';
            
            Debug::log("Looking for admin layout at: $adminLayoutPath");
            
            if (!file_exists($adminLayoutPath)) {
                throw new \Exception("Admin layout file not found: $adminLayoutPath");
            }
            
            // Include the admin layout with the content
            Debug::log("Including admin layout");
            include $adminLayoutPath;
            
        } catch (\Throwable $e) {
            // Clean the buffer in case of error
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            
            Debug::log("Exception in renderAdminView: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            
            // Fallback to error view
            view('errors.500', [
                'message' => 'Error al carregar la vista: ' . $e->getMessage(),
                'details' => [
                    'view' => $viewName,
                    'path' => $fullViewPath ?? 'unknown',
                    'error' => $e->getMessage()
                ]
            ]);
        }
    }

    public function updateStock(Request $request)
{
    try {
        if (!Auth::check() || !Auth::isAdmin()) {
            redirect('/auth/show-login.php?error=unauthorized')->send();
            return;
        }

        $producte = \App\Models\Producte::findOrFail($request->id);
        $nouEstoc = (int)$request->estoc;

        if ($nouEstoc < 0) {
            flashError('L\'estoc no pot ser negatiu.');
            redirect('/admin/products.php')->send();
            return;
        }

        $producte->estoc = $nouEstoc;
        $producte->update();

        flashSuccess('Estoc actualitzat correctament.');
        redirect('/admin/products.php')->send();
    } catch (\Throwable $e) {
        flashError('Error al actualitzar l\'estoc: ' . $e->getMessage());
        redirect('/admin/products.php')->send();
    }
}
}
