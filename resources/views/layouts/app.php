<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jardineria - <?= $title ?? 'Botiga Online' ?></title>
        <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/tooltip.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <script>
        // Set authentication status for JavaScript
        var isLoggedIn = <?= json_encode(\App\Core\Auth::check()) ?>;
        var isAdmin = <?= json_encode(\App\Core\Auth::check() && \App\Core\Auth::isAdmin()) ?>;
        // Make BASE_URL available to JavaScript
        var BASE_URL = <?= json_encode(BASE_URL) ?>;
    </script>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <main class="container mt-5 pt-5 flex-fill">
        <?= $content ?>
    </main>
    
    <?php include __DIR__ . '/partials/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>/js/ui.js"></script>
    <script src="<?= BASE_URL ?>/js/tooltip.js"></script>
    <script src="<?= BASE_URL ?>/js/main.js"></script>
</body>
</html>
