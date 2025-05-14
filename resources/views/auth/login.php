<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tenda de Jardineria</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body class="login-page">
    <header class="login-page">
        <h1>Tenda de Jardineria</h1>
        <div>
            <a href="<?= BASE_URL ?>/index.php">Pàgina principal</a>
        </div>
    </header>

    <div class="container">
        <h1>Iniciar Sessió</h1>
        
        <?php if (session()->hasFlash('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <form class="form-container" action="<?= BASE_URL ?>/auth/login.php" method="POST">
            <div class="form-group">
                <label for="username">Nom d'usuari</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contrasenya</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar Sessió</button>
        </form>
        <p>Encara no tens un compte? <a href="<?= BASE_URL ?>/auth/show-register.php">Registra't aquí</a></p>
    </div>
</body>
</html>
