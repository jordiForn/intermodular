<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <script src="<?= BASE_URL ?>/js/visibility.js" defer></script>
    <title>Contacte</title>
</head>
<body>
    <header>
        <h1>Formulari de Contacte</h1>
        <div>
            <a href="<?= BASE_URL ?>/index.php">Pàgina principal</a>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <form id="contact-form" action="<?= BASE_URL ?>/clients/store-contact.php" method="POST">
                <label for="name" id="name-label" style="display: none;">Nom:</label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($username); ?>" style="display: none;">

                <label for="email" id="email-label" style="display: none;">Correu electrònic:</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>" style="display: none;">

                <label for="phone" id="phone-label" style="display: none;">Telèfon:</label>
                <input type="tel" id="phone" name="phone" required value="<?php echo htmlspecialchars($phone); ?>" style="display: none;">

                <label for="notes">Missatge:</label>
                <textarea id="notes" name="notes" required></textarea>

                <button type="submit">Enviar missatge</button>
            </form>
        </div>
    </div>
</body>
</html>
