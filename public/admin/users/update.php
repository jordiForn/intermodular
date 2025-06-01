<?php
ob_start();
require_once __DIR__ . '/../../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Core\Response;
use App\Models\User;
use App\Core\Debug;
use App\Core\Request;

// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    Response::redirect('/auth/show-login.php', ['error' => 'Accés denegat. Has d\'iniciar sessió com a administrador.']);
    exit;
}

$request = new Request();
// Get user ID from POST
$id = (int)$request->input('id');

// Find user

$user = User::find($id);

if (!$user) {
    redirect('/admin/users/index.php')->with('error', 'Usuari no trobat.')->send();
    exit;
}

// Get form data
$username = $request->input('username', '');
$email = $request->input('email', '');
$password = $request->input('password', '');
$role = $request->input('role', '');

// Validate input
$errors = [];

// Username validation
if (empty($username)) {
    $errors['username'] = 'El nom d\'usuari és obligatori.';
} elseif ($username !== $user->username && User::findByUsername($username)) {
    $errors['username'] = 'Aquest nom d\'usuari ja està en ús.';
}

// Email validation
if (empty($email)) {
    $errors['email'] = 'L\'email és obligatori.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Format d\'email invàlid.';
} elseif ($email !== $user->email && User::findByEmail($email)) {
    $errors['email'] = 'Aquest email ja està registrat.';
}

// Password validation - only if provided
if (!empty($password) && strlen($password) < 6) {
    $errors['password'] = 'La contrasenya ha de tenir almenys 6 caràcters.';
}

// Role validation
$validRoles = ['user', 'admin'];
if (empty($role) || !in_array($role, $validRoles)) {
    $errors['role'] = 'El rol seleccionat no és vàlid.';
}

// If there are errors, redirect back with errors
if (!empty($errors)) {
    back()->withErrors($errors)->withInput([
        'username' => $username,
        'email' => $email,
        'role' => $role
    ])->send();
    exit;
}

try {
    // Update user
    $user->username = $username;
    $user->email = $email;

    // Only update password if provided
    if (!empty($password)) {
        $user->password = password_hash($password, PASSWORD_DEFAULT);
    }

    $user->role = $role;
    $user->updated_at = date('Y-m-d H:i:s');

    if ($user->save()) {
        redirect('/admin/users/index.php')->with('success', 'Usuari actualitzat correctament.')->send();
    } else {
        back()->with('error', 'Error en actualitzar l\'usuari.')->withInput([
            'username' => $username,
            'email' => $email,
            'role' => $role
        ])->send();
    }
} catch (\Exception $e) {
    Debug::log("Error updating user: " . $e->getMessage());
    back()->with('error', 'Error en actualitzar l\'usuari: ' . $e->getMessage())->withInput([
        'username' => $username,
        'email' => $email,
        'role' => $role
    ])->send();
}
ob_end_flush();