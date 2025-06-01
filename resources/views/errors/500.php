<?php
$title = 'Error del Servidor - Intermodular';
$message = $message ?? 'S\'ha produït un error intern del servidor.';
$details = $details ?? [];
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .error-card {
            max-width: 600px;
            width: 100%;
            margin: 20px;
        }
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <div class="error-icon mb-4">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h1 class="h2 mb-3">Error del Servidor</h1>
                    <p class="text-muted mb-4"><?= htmlspecialchars($message) ?></p>
                    
                    <?php if (!empty($details) && defined('DEBUG') && DEBUG): ?>
                        <div class="alert alert-warning text-start">
                            <h6><i class="fas fa-bug me-2"></i>Detalls de Debug:</h6>
                            <?php foreach ($details as $key => $value): ?>
                                <p class="mb-1"><strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars($value) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?= BASE_URL ?>/admin/" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Tornar al Dashboard
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Tornar Enrere
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
