<?php
declare(strict_types=1);

namespace App\Http\Validators;
use App\Core\Request;
use App\Core\Auth;

class ContactValidator
{
    public static function validate(Request $request): void
    {
        $errors = [];

        // Message is always required
        if (empty(trim($request->notes ?? ''))) {
            $errors['notes'] = 'El missatge és obligatori.';
        }
        
        // If user is not authenticated, validate name and email
        if (!Auth::check()) {
            if (empty(trim($request->name ?? ''))) {
                $errors['name'] = 'El nom és obligatori.';
            }
            
            if (empty(trim($request->email ?? ''))) {
                $errors['email'] = 'L\'email és obligatori.';
            } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'L\'email no és vàlid.';
            }
        }

        if ($errors) {
            back()->withErrors($errors)->withInput([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'notes' => $request->notes
            ])->send();
        }
    }
}
