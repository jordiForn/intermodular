<?php
require_once __DIR__ . '/../../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Core\Request;
use App\Models\User;
use App\Core\Debug;

// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    Response::redirect('/auth/show-login.php', ['error' => 'Accés denegat. Has d\'iniciar sessió com a administrador.']);
    exit;
}

$request = new Request();

// Validate input
$errors = [];

// Username validation
if (empty($request->username)) {
    $errors['username'] = 'El nom d\'usuari és obligatori.';
} elseif (User::findByUsername($request->username)) {
    $errors['username'] = 'Aquest nom d\'usuari ja està en ús.';
}

// Email validation
if (empty($request->email)) {
    $errors['email'] = 'L\'email és obligatori.';
} elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Format d\'email invàlid.';
} elseif (User::findByEmail($request->email)) {
    $errors['email'] = 'Aquest email ja està registrat.';
}

// Password validation
if (empty($request->password)) {
    $errors['password'] = 'La contrasenya és obligatòria.';
} elseif (strlen($request->password) < 6) {
    $errors['password'] = 'La contrasenya ha de tenir almenys 6 caràcters.';
}

// Role validation
$validRoles = ['user', 'admin'];
if (empty($request->role) || !in_array($request->role, $validRoles)) {
    $errors['role'] = 'El rol seleccionat no és vàlid.';
}

// If there are errors, redirect back with errors
if (!empty($errors)) {
    back()->withErrors($errors)->withInput([
        'username' => $request->username,
        'email' => $request->email,
        'role' => $request->role
    ])->send();
    exit;
}

try {
    // Create new user
    $user = new User();
    $user->username = $request->username;
    $user->email = $request->email;
    $user->password = password_hash($request->password, PASSWORD_DEFAULT);
    $user->role = $request->role;
    $user->created_at = date('Y-m-d H:i:s');
    $user->updated_at = date('Y-m-d H:i:s');
    
    if ($user->save()) {
        // Redirect to users list with success message
        redirect('/admin/users/index.php')->with('success', 'Usuari creat correctament.')->send();
    } else {
        // Redirect back with error
        back()->with('error', 'Error en crear l\'usuari.')->withInput([
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role
        ])->send();
    }
} catch (\Exception $e) {
    Debug::log("Error creating user: " . $e->getMessage());
    back()->with('error', 'Error en crear l\'usuari: ' . $e->getMessage())->withInput([
        'username' => $request->username,
        'email' => $request->email,
        'role' => $request->role
    ])->send();
}
