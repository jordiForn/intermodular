<?php
require_once __DIR__ . '/../../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Models\User;

// Check if user is authenticated and is admin
if (!Auth::check() || !Auth::isAdmin()) {
    header('Location: ' . BASE_URL . '/auth/show-login.php?error=unauthorized');
    exit;
}

try {
    // Get all users
    $users = User::all();
} catch (\Throwable $e) {
    $users = [];
    request()->session()->setFlash('error', 'Error carregant els usuaris: ' . $e->getMessage());
}

// Set page title
$title = 'Gestió d\'Usuaris';

// Start output buffering for content
ob_start();

// Include the users index view content
include __DIR__ . '/../../../resources/views/admin/users/index.php';

// Get the content and clean the buffer
$content = ob_get_clean();

// Include the admin layout
include __DIR__ . '/../../../resources/views/layouts/admin.php';
?>
