<?php
declare(strict_types=1);

namespace App\Http\Validators;

use App\Core\Request;
use App\Models\User;

class LoginValidator
{
    public static function validate(Request $request): array
    {
        $errors = [];
        
        // Validate username
        $username = $request->input('username');
        if (empty($username)) {
            $errors['username'] = 'Username is required';
        }
        
        // Validate password
        $password = $request->input('password');
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        }
        
        // Check if user exists
        if (empty($errors)) {
            $user = User::findByUsername($username);
            if (!$user) {
                $errors['username'] = 'User not found';
            } elseif (!password_verify($password, $user->password)) {
                $errors['password'] = 'Invalid password';
            }
        }
        
        return $errors;
    }
}
