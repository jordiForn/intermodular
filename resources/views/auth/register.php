<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar-se - Tenda de Jardineria</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body class="signup-page">
    <header class="signup-page">
        <h1>Tenda de Jardineria</h1>
        <div>
            <a href="<?= BASE_URL ?>/index.php">Pàgina principal</a>
        </div>
    </header>

    <div class="container">
        <h1>Registrar-se</h1>
        
        <?php if (session()->hasFlash('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors = session()->getFlash('errors', []))): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form-container" action="<?= BASE_URL ?>/auth/signup.php" method="POST">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars(session()->getFlash('old', [])['name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="surname">Cognoms</label>
                <input type="text" id="surname" name="surname" value="<?= htmlspecialchars(session()->getFlash('old', [])['surname'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Correu electrònic</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars(session()->getFlash('old', [])['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="username">Nom d'usuari</label>
                <input type="text" id="username" name="username" required value="<?= htmlspecialchars(session()->getFlash('old', [])['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="tlf">Telèfon</label>
                <input type="text" id="tlf" name="tlf" required value="<?= htmlspecialchars(session()->getFlash('old', [])['tlf'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Contrasenya</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Confirmar contrasenya</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            <button type="submit">Registrar-se</button>
        </form>
        <p>Ja tens un compte? <a href="<?= BASE_URL ?>/auth/show-login.php">Inicia sessió aquí</a></p>
    </div>
</body>
</html>
