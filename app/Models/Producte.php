<?php 
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use App\Core\QueryBuilder;
use App\Core\Debug;

class Producte extends Model
{
    protected static string $table = 'productes';
    protected static array $fillable = ['nom', 'descripcio', 'preu', 'estoc', 'categoria', 'imatge', 'detalls'];
    protected static array $relations = [];

    /** @override */
    public function insert(): bool
    {
        try {
            Debug::log("Starting product insertion for: " . ($this->nom ?? 'unnamed'));
        
            // Ensure all required fields have values
            $nom = trim($this->nom ?? '');
            $descripcio = trim($this->descripcio ?? '');
            $preu = (float)($this->preu ?? 0);
            $estoc = (int)($this->estoc ?? 0);
            $categoria = trim($this->categoria ?? '');
            $imatge = trim($this->imatge ?? '');
            $detalls = trim($this->detalls ?? '');
            
            // Validate required fields
            if (empty($nom)) {
                throw new \Exception("Product name is required");
            }
            if (empty($descripcio)) {
                throw new \Exception("Product description is required");
            }
            if (empty($categoria)) {
                throw new \Exception("Product category is required");
            }
            if ($preu <= 0) {
                throw new \Exception("Product price must be greater than 0");
            }
            if ($estoc < 0) {
                throw new \Exception("Product stock cannot be negative");
            }
            
            // Strict validation against only valid categories
            $validCategories = self::getValidCategories();
            if (!in_array($categoria, $validCategories)) {
                Debug::log("Invalid category: '$categoria'. Valid categories: " . implode(', ', $validCategories));
                throw new \Exception("Invalid category: '$categoria'. Must be one of: " . implode(', ', $validCategories));
            }
            
            // Construct the SQL query with proper escaping
            $sql = "INSERT INTO `" . self::$table . "` (`nom`, `descripcio`, `preu`, `estoc`, `categoria`, `imatge`, `detalls`) VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $nom,
                $descripcio,
                $preu,
                $estoc,
                $categoria,
                $imatge,
                $detalls
            ];
            
            Debug::log("SQL Query: " . $sql);
            Debug::log("Parameters: " . json_encode($params, JSON_UNESCAPED_UNICODE));
            Debug::log("Parameter types: " . json_encode(array_map('gettype', $params)));
            
            // Execute the insert using the correct DB method
            $insertId = DB::insert(self::$table, [
    'nom'        => $nom,
    'descripcio' => $descripcio,
    'preu'       => $preu,
    'estoc'      => $estoc,
    'categoria'  => $categoria,
    'imatge'     => $imatge,
    'detalls'    => $detalls
]);
            
            if ($insertId === false || $insertId === null) {
                throw new \Exception("Failed to insert product - no ID returned");
            }
            
            $this->id = $insertId;
            
            Debug::log("Product inserted successfully with ID: " . $this->id);
            return true;
            
        } catch (\Throwable $e) {
            Debug::log("Error inserting product: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Database insertion failed: " . $e->getMessage());
        }
    }

    /** @override */
    public function update(): bool
    {
        try {
            Debug::log("Starting product update for ID: " . ($this->id ?? 'unknown'));
            
            if (empty($this->id)) {
                throw new \Exception("Cannot update product without ID");
            }
            
            // Ensure all required fields have values
            $nom = trim($this->nom ?? '');
            $descripcio = trim($this->descripcio ?? '');
            $preu = (float)($this->preu ?? 0);
            $estoc = (int)($this->estoc ?? 0);
            $categoria = trim($this->categoria ?? '');
            $imatge = trim($this->imatge ?? '');
            $detalls = trim($this->detalls ?? '');
            
            // Validate required fields
            if (empty($nom)) {
                throw new \Exception("Product name is required");
            }
            if (empty($descripcio)) {
                throw new \Exception("Product description is required");
            }
            if (empty($categoria)) {
                throw new \Exception("Product category is required");
            }
            if ($preu <= 0) {
                throw new \Exception("Product price must be greater than 0");
            }
            if ($estoc < 0) {
                throw new \Exception("Product stock cannot be negative");
            }
            
            $sql = "UPDATE `" . self::$table . "` SET `nom` = ?, `descripcio` = ?, `preu` = ?, `estoc` = ?, `categoria` = ?, `imatge` = ?, `detalls` = ? WHERE `id` = ?";
            
            $params = [
                $nom,
                $descripcio,
                $preu,
                $estoc,
                $categoria,
                $imatge,
                $detalls,
                $this->id
            ];
            
            Debug::log("SQL Query: " . $sql);
            Debug::log("Parameters: " . json_encode($params, JSON_UNESCAPED_UNICODE));
            
            $result = DB::update($sql, $params);
            
            Debug::log("Product updated successfully");
            return true;
            
        } catch (\Throwable $e) {
            Debug::log("Error updating product: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Database update failed: " . $e->getMessage());
        }
    }

    public static function withCategoria(string $categoria): QueryBuilder
    {
        return self::where('categoria', $categoria);
    }

    public static function withBaixEstoc(int $limit = 5): QueryBuilder
    {
        return self::where('estoc', '<', $limit);
    }

    /**
     * Get the hardcoded valid categories (production-safe)
     * 
     * @return array Array of valid categories
     */
    public static function getValidCategories(): array
    {
        // Return only the valid production categories
        return [
            'Plantes i llavors',
            'Terra i adobs',
            'Ferramentes'
        ];
    }

    /**
     * Get all categories from the database (with filtering for invalid entries)
     * 
     * @return array Array of valid categories
     */
    public static function getCategories(): array
    {
        try {
            // Get the enum values directly from the database schema
            $query = "SHOW COLUMNS FROM productes WHERE Field = 'categoria'";
            $stmt = DB::connection()->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($result && isset($result['Type'])) {
                // Parse the enum values from the type definition
                // Format is typically: enum('value1','value2','value3')
                preg_match_all("/'(.*?)'/", $result['Type'], $matches);
                
                if (isset($matches[1]) && !empty($matches[1])) {
                    $dbCategories = $matches[1];
                    
                    // Filter out any test/invalid categories
                    $validCategories = self::getValidCategories();
                    $filteredCategories = array_intersect($dbCategories, $validCategories);
                    
                    Debug::log("Database categories: " . json_encode($dbCategories));
                    Debug::log("Filtered categories: " . json_encode($filteredCategories));
                    
                    // If we have valid categories from DB, use them, otherwise use hardcoded
                    return !empty($filteredCategories) ? array_values($filteredCategories) : $validCategories;
                }
            }
            
            // Fallback to hardcoded values if database query fails
            Debug::log("Using fallback categories");
            return self::getValidCategories();
        } catch (\Throwable $e) {
            Debug::log("Error getting categories: " . $e->getMessage());
            // Fallback to hardcoded values
            return self::getValidCategories();
        }
    }
    
    /**
     * Check if a category is valid
     * 
     * @param string $category The category to check
     * @return bool True if the category is valid, false otherwise
     */
    public static function isValidCategory(string $category): bool
    {
        $validCategories = self::getValidCategories();
        return in_array($category, $validCategories);
    }
    
    /**
     * Factory method to create a new product
     * 
     * @param array $data The product data
     * @return Producte The new product instance
     */
    public static function createProduct(array $data): Producte
    {
        $product = new self();
        
        foreach (self::$fillable as $field) {
            if (isset($data[$field])) {
                $product->$field = $data[$field];
            }
        }
        
        return $product;
    }
    
    /**
     * Save the product to the database
     * 
     * @return bool True if the product was saved successfully, false otherwise
     */
    public function save(): bool
    {
        try {
            Debug::log("Saving product: " . json_encode([
                'nom' => $this->nom ?? 'not set',
                'categoria' => $this->categoria ?? 'not set',
                'preu' => $this->preu ?? 'not set',
                'estoc' => $this->estoc ?? 'not set'
            ]));
            
            if (isset($this->id)) {
                // Update existing record
                return $this->update();
            } else {
                // Insert new record
                return $this->insert();
            }
        } catch (\Throwable $e) {
            Debug::log("Error saving product: " . $e->getMessage());
            Debug::log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
    
    /**
     * Get products by category
     * 
     * @param string $category The category to filter by
     * @return array Array of products in the category
     */
    public static function getByCategory(string $category): array
    {
        return self::where('categoria', $category)->get();
    }
    
    /**
     * Search products by name
     * 
     * @param string $query The search query
     * @return array Array of matching products
     */
    public static function search(string $query): array
    {
        return self::where('nom', 'LIKE', "%{$query}%")->get();
    }
    
    /**
     * Get the product's image URL
     * 
     * @return string The image URL
     */
    public function getImageUrl(): string
    {
        if (empty($this->imatge)) {
            return '/images/default-product.jpg';
        }
        
        if (strpos($this->imatge, 'http') === 0) {
            return $this->imatge;
        }
        
        return '/images/' . $this->imatge;
    }
    
    /**
     * Format the price with currency symbol
     * 
     * @return string The formatted price
     */
    public function getFormattedPrice(): string
    {
        return number_format($this->preu, 2, ',', '.') . ' €';
    }
    
    /**
     * Check if the product is in stock
     * 
     * @return bool True if the product is in stock, false otherwise
     */
    public function isInStock(): bool
    {
        return $this->estoc > 0;
    }
    
    /**
     * Get the stock status text
     * 
     * @return string The stock status text
     */
    public function getStockStatus(): string
    {
        if ($this->estoc > 10) {
            return 'En estoc';
        } elseif ($this->estoc > 0) {
            return 'Poques unitats';
        } else {
            return 'Fora d\'estoc';
        }
    }
}
