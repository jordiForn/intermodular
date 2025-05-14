<?php
use App\Core\Auth;
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalls del Producte</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <script src="<?= BASE_URL ?>/js/script.js" defer></script>
</head>
<body>
    <header>
        <h1>Detalls del Producte</h1>
        <div><a href="<?= BASE_URL ?>/index.php">Pàgina principal</a></div>
    </header>

    <div class="product-details">
        <div class="container">
            <h2><?= htmlspecialchars($producte->nom) ?></h2>
            <img src="<?= BASE_URL ?>/images/<?= htmlspecialchars($producte->imatge) ?>" alt="<?= htmlspecialchars($producte->nom) ?>">
            <p><?= htmlspecialchars($producte->descripcio) ?></p>
            <p class="price"><?= number_format($producte->preu, 2, ",", ".") ?>€</p>
            <p class="stock">Estoc disponible: <?= number_format($producte->estoc, 0, ",", ".") ?></p>
            
            <div class="actions">
                <button onclick="addToCart('<?= addslashes($producte->nom) ?>', <?= $producte->preu ?>)">Afegir al Carret</button>
                <a href="<?= BASE_URL ?>/index.php" class="back-link">Tornar a la botiga</a>
            </div>
        </div>
    </div>
</body>
</html>
