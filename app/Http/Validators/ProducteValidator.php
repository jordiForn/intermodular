<?php
declare(strict_types=1);

namespace App\Http\Validators;
use App\Core\Request;

class ProducteValidator
{
    public static function validate(Request $request): void
    {
        $errors = [];

        $nom_valid = trim($request->nom ?? '') !== '';
        $descripcio_valid = trim($request->descripcio ?? '') !== '';
        $preu_valid = filter_var($request->preu, FILTER_VALIDATE_FLOAT) && (float)$request->preu > 0;
        $estoc_valid = filter_var($request->estoc, FILTER_VALIDATE_INT) && (int)$request->estoc >= 0;
        $categoria_valid = trim($request->categoria ?? '') !== '';

        if (!$nom_valid) {
            $errors['nom'] = 'El nom és obligatori.';
        }

        if (!$descripcio_valid) {
            $errors['descripcio'] = 'La descripció és obligatòria.';
        }

        if (!$preu_valid) {
            $errors['preu'] = 'El preu ha de ser un número positiu.';
        }

        if (!$estoc_valid) {
            $errors['estoc'] = 'L\'estoc ha de ser un número enter no negatiu.';
        }

        if (!$categoria_valid) {
            $errors['categoria'] = 'La categoria és obligatòria.';
        }

        if ($errors) {
            back()->withErrors($errors)->withInput([
                'nom' => $request->nom,
                'descripcio' => $request->descripcio,
                'preu' => $request->preu,
                'estoc' => $request->estoc,
                'categoria' => $request->categoria,
                'imatge' => $request->imatge,
                'detalls' => $request->detalls,
            ])->send();
        }
    }
}
