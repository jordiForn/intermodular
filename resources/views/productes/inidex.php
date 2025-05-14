<?php
use App\Core\Auth;
?>

<!DOCTYPE html>
<html lang="ca" class="index-page">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenda de Jardineria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css?v<?php echo time(); ?>">
    <script src="<?= BASE_URL ?>/js/script.js" defer></script>
    <script src="<?= BASE_URL ?>/js/visibility.js" defer></script>
</head>

<body class="index-page">
    <header>
        <h1>Tenda de Jardineria</h1>
        <div>
            <a href="#" id="search-icon"><i class="fas fa-search"></i></a>
            <input type="text" id="search-input" placeholder="Buscar productes..." style="display: none;">
            <a href="<?= BASE_URL ?>/contact.php" id="contact-icon"><i class="fas fa-envelope"></i></a>
            <?php if (!Auth::check()): ?>
                <a href="<?= BASE_URL ?>/auth/show-login.php"><i class="fas fa-user"></i></a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/logout.php" id="logout-link"><i class="fas fa-sign-out-alt"></i></a>
                <?php if (Auth::role() === '1'): ?>
                    <a href="#" id="menu-icon"><i class="fas fa-bars"></i></a>
                <?php endif; ?>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/cart.html"><i class="fas fa-shopping-cart"></i></a>
        </div>
    </header>

    <?php if (Auth::check() && Auth::role() === '1'): ?>
    <nav id="admin-menu" style="display: none;">
        <ul>
            <li><a href="<?= BASE_URL ?>/productes/create.php">Afegir un producte</a></li>
            <li><a href="<?= BASE_URL ?>/productes/edit-list.php">Canviar un producte</a></li>
            <li><a href="<?= BASE_URL ?>/clients/index.php">Editar usuaris</a></li>
        </ul>
    </nav>
    <?php endif; ?>

    <div class="container">
        <h2>Llista de Productes</h2>
        <a href="#service-category">
            Busques serveis?
        </a>
        <?php foreach ($categories as $categoria => $productes): ?>
            <div class="product-category">
                <button class="toggle-button"><?= htmlspecialchars($categoria) ?></button>
                <div class="product-list">
                    <?php foreach ($productes as $producte): ?>
                        <a href="<?= BASE_URL ?>/productes/show.php?id=<?= htmlspecialchars($producte->id) ?>" class="product-link">
                            <div class="product-card">
                                <img src="<?= BASE_URL ?>/images/<?= htmlspecialchars($producte->imatge) ?>" alt="<?= htmlspecialchars($producte->nom) ?>">
                                <h3><?= htmlspecialchars($producte->nom) ?></h3>
                                <p><?= htmlspecialchars($producte->descripcio) ?></p>
                                <p class="price"><?= number_format($producte->preu, 2, ",", ".") ?>€</p>
                                <p>Estoc disponible: <?= number_format($producte->estoc, 0, ",", ".") ?></p>
                                <div class="tooltip-container">
                                    <button onclick="addToCart('<?= addslashes($producte->nom) ?>', <?= $producte->preu ?>)">Afegir al Carret</button>
                                    <span class="tooltip-text">0 ítems - 0,00€</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="container">
        <div class="service-category" id="service-category">
            <button class="toggle-button">🏡 Serveis per a jardins</button>
            <div class="service-garden">
                <?php
                $jardins = (new \App\Http\Controllers\ServeiController())->getJardins();
                
                if (!empty($jardins)) {
                    foreach ($jardins as $servei) {
                        $nom = htmlspecialchars($servei->nom);
                        $preu_base = number_format($servei->preu_base, 2, ",", "."); 
                        
                        echo "<div class='service-card'>";
                        echo "<h3>$nom</h3>";
                        echo "<p class='price'>$preu_base €/h</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hi ha serveis disponibles per a jardins.</p>";
                }
                ?>
            </div>
        </div>

        <div class="service-category" id="service-category">
            <button class="toggle-button">🏊 Serveis per a piscines</button>
            <div class="service-pool">
                <?php
                $piscines = (new \App\Http\Controllers\ServeiController())->getPiscines();
                
                if (!empty($piscines)) {
                    foreach ($piscines as $servei) {
                        $nom = htmlspecialchars($servei->nom);
                        $preu_base = number_format($servei->preu_base, 2, ",", "."); 
                        
                        echo "<div class='service-card'>";
                        echo "<h3>$nom</h3>";
                        echo "<p class='price'>$preu_base €/h</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hi ha serveis disponibles per a piscines.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
