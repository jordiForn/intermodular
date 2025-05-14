<?php
declare(strict_types=1);

namespace App\Http\Validators;
use App\Core\Request;

class ContactValidator
{
    public static function validate(Request $request): void
    {
        $errors = [];

        $notes_valid = trim($request->notes ?? '') !== '';
        
        if (!$notes_valid) {
            $errors['notes'] = 'El missatge és obligatori.';
        }

        if ($errors) {
            back()->withErrors($errors)->withInput([
                'notes' => $request->notes
            ])->send();
        }
    }
}
