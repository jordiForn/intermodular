<?php
ob_start();
require_once __DIR__ . '/../../../bootstrap/bootstrap.php';

use App\Core\Auth;

// Check if user is authenticated and is admin
if (!Auth::check() || !Auth::isAdmin()) {
    header('Location: ' . BASE_URL . '/auth/show-login.php?error=unauthorized');
    exit;
}

// Set page title
$title = 'Crear Nou Usuari';

// Start output buffering for content
ob_start();

// Include the create user view content
include __DIR__ . '/../../../resources/views/admin/users/create.php';

// Get the content and clean the buffer
$content = ob_get_clean();

// Include the admin layout
include __DIR__ . '/../../../resources/views/layouts/admin.php';
?>
<?php
ob_end_flush();