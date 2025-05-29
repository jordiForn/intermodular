<?php
use App\Core\Auth;
use App\Models\Producte;
use App\Models\Client;
use App\Models\Comanda;
use App\Models\Servei;

$user = Auth::user();
$userName = $user ? $user->username : 'Admin';

// Get dashboard statistics
try {
    $totalProducts = Producte::count();
    $totalClients = Client::count();
    $totalOrders = Comanda::count();
    $totalServices = Servei::count();
    
    // Get low stock products (stock <= 5)
    $lowStockProducts = Producte::where('stock', '<=', 5)->limit(5)->get();
    
    // Get recent orders (last 5)
    $recentOrders = Comanda::orderBy('data_comanda', 'DESC')->limit(5)->get();
    
} catch (\Throwable $e) {
    $totalProducts = 0;
    $totalClients = 0;
    $totalOrders = 0;
    $totalServices = 0;
    $lowStockProducts = [];
    $recentOrders = [];
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panell d'Administració - Intermodular</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .admin-welcome {
            background: linear-gradient(135deg, #4daa57, #587d71);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .admin-sidebar {
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
            padding: 1rem;
        }
        .admin-nav-link {
            color: #587d71;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 5px;
            display: block;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }
        .admin-nav-link:hover {
            background-color: #4daa57;
            color: white;
        }
        .admin-nav-link.active {
            background-color: #754668;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #587d71;">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= BASE_URL ?>">
                <i class="fas fa-leaf me-2"></i>Intermodular - Admin
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i><?= htmlspecialchars($userName) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>"><i class="fas fa-home me-2"></i>Anar al lloc web</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Tancar sessió</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar">
                <div class="d-flex flex-column">
                    <a href="<?= BASE_URL ?>/admin/" class="admin-nav-link active">
                        <i class="fas fa-tachometer-alt me-2"></i>Tauler de control
                    </a>
                    <a href="<?= BASE_URL ?>/admin/products.php" class="admin-nav-link">
                        <i class="fas fa-seedling me-2"></i>Productes
                    </a>
                    <a href="<?= BASE_URL ?>/admin/clients.php" class="admin-nav-link">
                        <i class="fas fa-users me-2"></i>Clients
                    </a>
                    <a href="<?= BASE_URL ?>/admin/orders.php" class="admin-nav-link">
                        <i class="fas fa-shopping-cart me-2"></i>Comandes
                    </a>
                    <a href="<?= BASE_URL ?>/serveis/" class="admin-nav-link">
                        <i class="fas fa-tools me-2"></i>Serveis
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <!-- Welcome Section -->
                    <div class="admin-welcome">
                        <h1><i class="fas fa-crown me-2"></i>Benvingut al Panell d'Administració</h1>
                        <p class="mb-0">Hola, <?= htmlspecialchars($userName) ?>! Aquí pots gestionar tots els aspectes del teu negoci de jardineria.</p>
                    </div>

                    <!-- Flash Messages -->
                    <?php if (session()->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= session()->getFlash('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlash('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card h-100" style="border-left: 4px solid #4daa57;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-muted">Productes</h6>
                                            <h3 class="mb-0"><?= $totalProducts ?></h3>
                                        </div>
                                        <div class="text-success">
                                            <i class="fas fa-seedling fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card h-100" style="border-left: 4px solid #754668;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-muted">Clients</h6>
                                            <h3 class="mb-0"><?= $totalClients ?></h3>
                                        </div>
                                        <div class="text-info">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card h-100" style="border-left: 4px solid #587d71;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-muted">Comandes</h6>
                                            <h3 class="mb-0"><?= $totalOrders ?></h3>
                                        </div>
                                        <div class="text-primary">
                                            <i class="fas fa-shopping-cart fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card h-100" style="border-left: 4px solid #b5dda4;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-muted">Serveis</h6>
                                            <h3 class="mb-0"><?= $totalServices ?></h3>
                                        </div>
                                        <div class="text-warning">
                                            <i class="fas fa-tools fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Accions ràpides</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <a href="<?= BASE_URL ?>/productes/create.php" class="btn btn-success w-100">
                                                <i class="fas fa-plus me-2"></i>Afegir Producte
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-primary w-100">
                                                <i class="fas fa-edit me-2"></i>Gestionar Productes
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="<?= BASE_URL ?>/admin/orders.php" class="btn btn-info w-100">
                                                <i class="fas fa-list me-2"></i>Veure Comandes
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="<?= BASE_URL ?>/admin/clients.php" class="btn btn-warning w-100">
                                                <i class="fas fa-user-friends me-2"></i>Gestionar Clients
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Products and Recent Orders -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Productes amb poc estoc</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($lowStockProducts)): ?>
                                        <p class="text-muted">Tots els productes tenen estoc suficient.</p>
                                    <?php else: ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($lowStockProducts as $product): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?= htmlspecialchars($product->nom) ?></strong>
                                                        <br>
                                                        <small class="text-muted">Estoc: <?= $product->stock ?></small>
                                                    </div>
                                                    <span class="badge bg-warning rounded-pill"><?= $product->stock ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-clock me-2 text-info"></i>Comandes recents</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($recentOrders)): ?>
                                        <p class="text-muted">No hi ha comandes recents.</p>
                                    <?php else: ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($recentOrders as $order): ?>
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>Comanda #<?= $order->id ?></strong>
                                                        <small><?= date('d/m/Y', strtotime($order->data_comanda)) ?></small>
                                                    </div>
                                                    <small class="text-muted">
                                                        Client: <?= htmlspecialchars($order->nom_client ?? 'Desconegut') ?>
                                                    </small>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/js/admin-dashboard.js"></script>
</body>
</html>
