<?php
ob_start();
require_once __DIR__ . '/../../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Models\User;

// Check if user is authenticated and is admin
if (!Auth::check() || !Auth::isAdmin()) {
    header('Location: ' . BASE_URL . '/auth/show-login.php?error=unauthorized');
    exit;
}

// Get user ID from URL
$userId = $_GET['id'] ?? null;
\App\Core\Debug::log('Valor de $userId en edit.php', ['userId' => $userId]);
if (!$userId) {
    request()->session()->setFlash('error', 'ID d\'usuari no especificat.');
    header('Location: ' . BASE_URL . '/admin/users/');
    exit;
}

try {
    // Get the user
    $user = User::find($userId);
    if (!$user) {
        request()->session()->setFlash('error', 'Usuari no trobat.');
        header('Location: ' . BASE_URL . '/admin/users/');
        exit;
    }
} catch (\Throwable $e) {
    request()->session()->setFlash('error', 'Error carregant l\'usuari: ' . $e->getMessage());
    header('Location: ' . BASE_URL . '/admin/users/');
    exit;
}

// Set page title
$title = 'Editar Usuari: ' . $user->username;

// Start output buffering for content
ob_start();

// Include the edit user view content
include __DIR__ . '/../../../resources/views/admin/users/edit.php';

// Get the content and clean the buffer
$content = ob_get_clean();

// Include the admin layout
include __DIR__ . '/../../../resources/views/layouts/admin.php';
?>
<?php
ob_end_flush();