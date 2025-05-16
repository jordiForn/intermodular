<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jardineria - <?= $title ?? 'Botiga Online' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/tooltip.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        // Set authentication status for JavaScript
        var isLoggedIn = <?= json_encode(\App\Core\Auth::check()) ?>;
        var isAdmin = <?= json_encode(\App\Core\Auth::check() && \App\Core\Auth::role() === 'admin') ?>;
        // Make BASE_URL available to JavaScript
        var BASE_URL = <?= json_encode(BASE_URL) ?>;
    </script>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <main>
        <?= $content ?>
    </main>
    
    <?php include __DIR__ . '/partials/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>/js/cart.js"></script>
    <script src="<?= BASE_URL ?>/js/ui.js"></script>
    <script src="<?= BASE_URL ?>/js/tooltip.js"></script>
    <script src="<?= BASE_URL ?>/js/main.js"></script>
</body>
</html>
