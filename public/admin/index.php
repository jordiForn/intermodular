<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Core\Auth;
use App\Models\User;
use App\Models\Client;
use App\Models\Producte;
use App\Models\Comanda;

// Check if user is authenticated and is admin
if (!Auth::check() || !Auth::isAdmin()) {
    header('Location: ' . BASE_URL . '/auth/show-login.php?error=unauthorized');
    exit;
}

// Get counts for dashboard
try {
    $userCount = count(User::all());
    $clientCount = count(Client::all());
    $productCount = count(Producte::all());
    $orderCount = count(Comanda::all());
    
    // Get recent users (last 5)
    $recentUsers = array_slice(User::all(), 0, 5);
    
    // Get current user
    $currentUser = Auth::user();
} catch (\Throwable $e) {
    $userCount = 0;
    $clientCount = 0;
    $productCount = 0;
    $orderCount = 0;
    $recentUsers = [];
    $currentUser = Auth::user();
}

// Set page title
$title = 'Dashboard Principal';

// Start output buffering for content
ob_start();
?>

<div class="mt-4">
    <!-- Welcome section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-danger rounded-circle me-3">
                            <span class="avatar-text"><?= strtoupper(substr($currentUser->username, 0, 1)) ?></span>
                        </div>
                        <div>
                            <h4 class="mb-1">Benvingut, <?= htmlspecialchars($currentUser->username) ?>!</h4>
                            <p class="text-muted mb-0">Panell d'Administració - <?= date('d/m/Y') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Usuaris</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $userCount ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-2">
                    <a href="<?= BASE_URL ?>/admin/users/" class="text-decoration-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Gestionar Usuaris</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Clients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $clientCount ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-2">
                    <a href="<?= BASE_URL ?>/admin/clients.php" class="text-decoration-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Gestionar Clients</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Productes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $productCount ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-leaf fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-2">
                    <a href="<?= BASE_URL ?>/admin/products.php" class="text-decoration-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Gestionar Productes</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Comandes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $orderCount ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-basket fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-2">
                    <a href="<?= BASE_URL ?>/admin/orders.php" class="text-decoration-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Gestionar Comandes</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-highlight">Accions Ràpides</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?= BASE_URL ?>/admin/users/create.php" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-user-plus mb-2 d-block fa-2x"></i>
                                Nou Usuari
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= BASE_URL ?>/productes/create.php" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-plus-circle mb-2 d-block fa-2x"></i>
                                Nou Producte
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= BASE_URL ?>/admin/orders.php" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-clipboard-list mb-2 d-block fa-2x"></i>
                                Veure Comandes
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= BASE_URL ?>" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-store mb-2 d-block fa-2x"></i>
                                Veure Botiga
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent users -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-highlight">Usuaris Recents</h5>
                        <a href="<?= BASE_URL ?>/admin/users/" class="btn btn-sm btn-outline-primary">
                            Veure Tots
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Usuari</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Data de creació</th>
                                    <th>Accions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentUsers as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-<?= $user->role === 'admin' ? 'danger' : 'success' ?> rounded-circle me-2">
                                                    <span class="avatar-text"><?= strtoupper(substr($user->username, 0, 1)) ?></span>
                                                </div>
                                                <?= htmlspecialchars($user->username) ?>
                                                <?php if ($user->id === $currentUser->id): ?>
                                                    <span class="badge bg-secondary ms-2">Tu</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($user->email) ?></td>
                                        <td>
                                            <?php if ($user->role === 'admin'): ?>
                                                <span class="badge bg-danger">Administrador</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Usuari</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($user->created_at)) ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/admin/users/edit.php?id=<?= $user->id ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content and clean the buffer
$content = ob_get_clean();

// Include the admin layout
include __DIR__ . '/../../resources/views/layouts/admin.php';
?>
