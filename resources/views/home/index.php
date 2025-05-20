<?php

use App\Models\Producte;
use App\Models\Servei;
use App\Core\DB;
use App\Core\QueryBuilder;

$qb = new QueryBuilder(Producte::class);
$qb->where('estoc', '>', 0);
$qb->orderByRaw("CASE 
    WHEN categoria = 'Plantes i llavors' THEN 1
    WHEN categoria = 'Terra i adobs' THEN 2
    WHEN categoria = 'Ferramentes' THEN 3
    ELSE 4
END, nom", '');
$productes = $qb->get();

$categories = [];
foreach ($productes as $producte) {
    $categories[$producte->categoria][] = $producte;
}
?>
    <div class="container mt-5 pt-4">
        <h2>Llista de Productes</h2>
        <a href="#service-category">Busques serveis?</a>
        <?php foreach ($categories as $categoria => $productes): ?>
            <div class="product-category">
                <button class="toggle-button"><?= htmlspecialchars($categoria) ?></button>
                <div class="product-list">
                    <?php foreach ($productes as $producte): ?>
                        <a href="public/productes/show.php?id=<?= htmlspecialchars($producte->id) ?>" class="product-link">
                            <div class="product-card">
                                <img src="../public/images/<?= htmlspecialchars($producte->imatge) ?>" alt="<?= htmlspecialchars($producte->nom) ?>">
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
                $serveisJardins = Servei::where('cat', 'jardins')->get();
                if (count($serveisJardins) > 0) {
                    foreach ($serveisJardins as $servei) {
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
                $serveisPiscines = Servei::where('cat', 'piscines')->get();
                if (count($serveisPiscines) > 0) {
                    foreach ($serveisPiscines as $servei) {
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