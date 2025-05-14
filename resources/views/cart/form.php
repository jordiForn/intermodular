<?php
use App\Core\Auth;
$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="ca" class="form-page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <script src="<?= BASE_URL ?>/js/script.js" defer></script>
    <title>Formulari de Compra</title>
</head>
<body class="form-page">
    <header>
        <h1>Formulari de Compra</h1>
        <div>
            <a href="<?= BASE_URL ?>/cart.html">Carret</a>
            <a href="<?= BASE_URL ?>/index.php">Pàgina principal</a>
        </div>
    </header>
    <div class="container">
        <div class="form-container">
            <form id="purchase-form" action="<?= BASE_URL ?>/process_order.php" method="POST">
                <label for="name">Nom:</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user['username'] ?? '') ?>">

                <label for="email">Correu electrònic:</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>">

                <label for="address">Direcció d'enviament:</label>
                <input type="text" id="address" name="address" required>

                <label for="phone">Telèfon:</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="notes">Notes adicionals (opcional):</label>
                <textarea id="notes" name="notes"></textarea>

                <input type="hidden" id="cart-data" name="cart_data">
                <input type="hidden" id="cart-total-hidden" name="cart_total" value="">

                <button type="submit" onclick="getTotal()">Confirmar compra</button>
            </form>
        </div>
    </div>
</body>
</html>
