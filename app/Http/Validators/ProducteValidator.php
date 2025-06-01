<?php
declare(strict_types=1);

namespace App\Http\Validators;

use App\Core\Request;
use App\Core\Debug;
use App\Models\Producte;

class ProducteValidator
{
    /**
     * Validate product data
     * 
     * @param Request $request The request object
     * @return void
     * @throws \Exception If validation fails
     */
    public static function validate(Request $request): void
    {
        Debug::log("ProducteValidator::validate called");
        Debug::log("Request data: " . json_encode([
            'nom' => $request->nom ?? 'not set',
            'descripcio' => $request->descripcio ?? 'not set',
            'preu' => $request->preu ?? 'not set',
            'estoc' => $request->estoc ?? 'not set',
            'categoria' => $request->categoria ?? 'not set'
        ]));
        
        $errors = [];
        
        // Validate product name
        $nom = trim($request->nom ?? '');
        if (empty($nom)) {
            $errors['nom'] = "El nom del producte és obligatori";
        } elseif (strlen($nom) < 2) {
            $errors['nom'] = "El nom ha de tenir almenys 2 caràcters";
        } elseif (strlen($nom) > 255) {
            $errors['nom'] = "El nom no pot tenir més de 255 caràcters";
        }
        
        // Validate description
        $descripcio = trim($request->descripcio ?? '');
        if (empty($descripcio)) {
            $errors['descripcio'] = "La descripció és obligatòria";
        } elseif (strlen($descripcio) < 2) {
            $errors['descripcio'] = "La descripció ha de tenir almenys 2 caràcters";
        }
        
        // Validate price
        $preu = $request->preu ?? '';
        if (empty($preu)) {
            $errors['preu'] = "El preu és obligatori";
        } elseif (!is_numeric($preu)) {
            $errors['preu'] = "El preu ha de ser un número";
        } elseif ((float)$preu <= 0) {
            $errors['preu'] = "El preu ha de ser un número positiu";
        }
        
        // Validate stock
        $estoc = $request->estoc ?? '';
        if ($estoc === '' || $estoc === null) {
            $errors['estoc'] = "L'estoc és obligatori";
        } elseif (!is_numeric($estoc)) {
            $errors['estoc'] = "L'estoc ha de ser un número";
        } elseif ((int)$estoc < 0) {
            $errors['estoc'] = "L'estoc no pot ser negatiu";
        }
        
        // Validate category with strict checking
        $categoria = trim($request->categoria ?? '');
        if (empty($categoria)) {
            $errors['categoria'] = "La categoria és obligatòria";
        } else {
            $validCategories = Producte::getValidCategories();
            Debug::log("Validating category: '{$categoria}' against valid categories: " . json_encode($validCategories));
            
            if (!in_array($categoria, $validCategories)) {
                $errors['categoria'] = "La categoria '{$categoria}' no és vàlida. Categories vàlides: " . implode(', ', $validCategories);
            }
        }
        
        // Validate image if provided
        if (!empty($request->imatge)) {
            $imatge = trim($request->imatge);
            if (strlen($imatge) > 255) {
                $errors['imatge'] = "La URL de la imatge no pot tenir més de 255 caràcters";
            }
        }
        
        // Validate details if provided
        if (!empty($request->detalls)) {
            $detalls = trim($request->detalls);
            if (strlen($detalls) > 200) {
                $errors['detalls'] = "Els detalls no poden tenir més de 200 caràcters";
            }
        }
        
        // If there are validation errors, throw an exception
        if (!empty($errors)) {
            Debug::log("Validation failed: " . json_encode($errors));
            
            // Store errors in session for display
            session()->setFlash('errors', $errors);
            
            // Store old input for form repopulation
            session()->setFlash('old', [
                'nom' => $request->nom ?? '',
                'descripcio' => $request->descripcio ?? '',
                'preu' => $request->preu ?? '',
                'estoc' => $request->estoc ?? '',
                'categoria' => $request->categoria ?? '',
                'imatge' => $request->imatge ?? '',
                'detalls' => $request->detalls ?? ''
            ]);
            
            throw new \Exception("Hi ha errors en el formulari. Revisa els camps marcats.");
        }
        
        Debug::log("Validation passed successfully");
    }
}
