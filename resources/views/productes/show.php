<?php
use App\Models\Producte;

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$producte = Producte::find($id);
if (!$producte) {
    header('Location: index.php');
    exit;
}
?>

<div class="product-details">
    <div class="container">
        <h2><?= htmlspecialchars($producte->nom) ?></h2>
        
        <?php if ($producte->imatge): ?>
            <img src="<?= imageUrl($producte->imatge, 400, 300) ?>" alt="<?= htmlspecialchars($producte->nom) ?>">
        <?php endif; ?>
        
        <p><?= nl2br(htmlspecialchars($producte->descripcio)) ?></p>
        <p class="price"><?= number_format($producte->preu, 2, ",", ".") ?>€</p>
        <p class="stock">Estoc disponible: <?= number_format($producte->estoc, 0, ",", ".") ?></p>
        
        <div class="tooltip-container">
            <button onclick="addToCart('<?= addslashes($producte->nom) ?>', <?= $producte->preu ?>, <?= $producte->id ?>, <?= $producte->estoc ?>)">Afegir al Carret</button>
            <span class="tooltip-text">0 ítems - 0,00€</span>
        </div>
        
        <a href="index.php" class="back-link">Tornar al catàleg</a>
    </div>
</div>
