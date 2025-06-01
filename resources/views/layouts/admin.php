<?php
// Start output buffering to prevent headers already sent errors
ob_start();

use App\Core\Auth;

// Ensure user is authenticated and is admin
if (!Auth::check() || !Auth::isAdmin()) {
    header('Location: ' . BASE_URL . '/auth/show-login.php?error=unauthorized');
    exit;
}

$user = Auth::user();
$userName = $user ? $user->username : 'Admin';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Panell d\'Administració' ?> - Intermodular</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/css/styles.css" rel="stylesheet">
    
    <!-- Admin specific styles -->
    <style>
        .admin-layout {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .admin-content {
            flex: 1;
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
        .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .avatar-sm {
            width: 30px;
            height: 30px;
        }
        .avatar-lg {
            width: 50px;
            height: 50px;
        }
        .avatar-text {
            font-size: 14px;
        }
        .avatar-lg .avatar-text {
            font-size: 24px;
        }
        .border-left-primary {
            border-left: 4px solid #4e73df;
        }
        .border-left-success {
            border-left: 4px solid #1cc88a;
        }
        .border-left-info {
            border-left: 4px solid #36b9cc;
        }
        .border-left-warning {
            border-left: 4px solid #f6c23e;
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
    
    <!-- Chart.js for dashboard charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Set authentication status for JavaScript
        var isLoggedIn = <?= json_encode(Auth::check()) ?>;
        var isAdmin = <?= json_encode(Auth::check() && Auth::isAdmin()) ?>;
        var BASE_URL = <?= json_encode(BASE_URL) ?>;
    </script>
</head>

<body class="admin-layout">
    <!-- Admin Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #587d71;">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= BASE_URL ?>/admin/">
                <i class="fas fa-leaf me-2"></i>Intermodular - Admin
            </a>
            
            <!-- Mobile menu toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/users/">
                            <i class="fas fa-users me-1"></i>Usuaris
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/products.php">
                            <i class="fas fa-seedling me-1"></i>Productes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/orders.php">
                            <i class="fas fa-shopping-cart me-1"></i>Comandes
                        </a>
                    </li>
                </ul>
                
                <div class="navbar-nav">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($userName) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>">
                                    <i class="fas fa-home me-2"></i>Anar al lloc web
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/auth/logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Tancar sessió
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="admin-content">
        <main class="container-fluid">
            <!-- Flash Messages -->
            <?php 
            $session = request()->session();
            if ($session->hasFlash('success')): 
            ?>
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= $session->getFlash('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($session->hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $session->getFlash('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <?= $content ?>
        </main>
    </div>

    <!-- Admin Footer -->
    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        &copy; <?= date('Y'); ?> Intermodular - Panell d'Administració
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        Versió 1.0 | Usuari: <?= htmlspecialchars($userName) ?>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Admin specific JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>
<?php
// Flush the output buffer
ob_end_flush();
?>
