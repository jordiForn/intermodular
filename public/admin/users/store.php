<?php
require_once __DIR__ . '/../../../bootstrap/bootstrap.php';
require_once __DIR__ . '/../../../app/Http/Controllers/AuthController.php';
use App\Core\Auth;
use App\Core\Response;
use App\Core\Request;
use App\Models\User;
use App\Core\Debug;
use App\Http\Controllers\AuthController;
// Check if user is authenticated and is an admin
if (!Auth::check() || !Auth::isAdmin()) {
    Response::redirect('/auth/show-login.php', ['error' => 'Accés denegat. Has d\'iniciar sessió com a administrador.']);
    exit;
}

$request = new Request();
//var_dump($request->all()); exit;
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


$data = [
    'username' => $request->username,
    'email' => $request->email,
    'password' => $request->password,
    'role' => $request->role,
];

\App\Core\Debug::log('Antes de llamar a adminRegisterUser', $data);
$user = AuthController::adminRegisterUser($data);

if ($user) {
    redirect('/admin/users/index.php')->with('success', 'Usuari creat correctament.')->send();
} else {
    back()->with('error', 'Error en crear l\'usuari.')->withInput($data)->send();
}

\App\Core\Debug::log('Validando errores', $errors);
if (!empty($errors)) {
    back()->withErrors($errors)->withInput([
        'username' => $request->username,
        'email' => $request->email,
        'role' => $request->role
    ])->send();
    exit;
}