<?php
declare(strict_types=1);

namespace App\Http\Validators;

use App\Core\Request;

class ComandaValidator
{
    public static function validate(Request $request): void
    {
        $errors = [];

        $total_valid = filter_var($request->total, FILTER_VALIDATE_FLOAT) && (float) $request->total > 0;
        $direccio_valid = trim($request->direccio_enviament ?? '') !== '';
        
        if (!$total_valid) {
            $errors['total'] = 'El total ha de ser un número positiu.';
        }

        if (!$direccio_valid) {
            $errors['direccio_enviament'] = 'La direcció d\'enviament és obligatòria.';
        }

        if ($errors) {
            back()->withErrors($errors)->withInput([
                'total' => $request->total,
                'direccio_enviament' => $request->direccio_enviament,
            ])->send();
        }
    }
}
