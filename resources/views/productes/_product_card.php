<div class="product-card">
    <?php if ($producte->imatge): ?>
        <img src="<?= imageUrl($producte->imatge, 300, 200) ?>" alt="<?= htmlspecialchars($producte->nom) ?>">
    <?php endif; ?>
    <h3><?= htmlspecialchars($producte->nom) ?></h3>
    <p><?= htmlspecialchars(substr($producte->descripcio, 0, 100)) . (strlen($producte->descripcio) > 100 ? '...' : '') ?></p>
    <p class="price"><?= number_format($producte->preu, 2) ?> €</p>
    <p>
        <span class="badge <?= $producte->estoc > 5 ? 'bg-success' : 'bg-warning' ?>">
            Estoc: <?= htmlspecialchars($producte->estoc) ?>
        </span>
    </p>
    <button 
        class="add-to-cart" 
        data-name="<?= htmlspecialchars($producte->nom) ?>" 
        data-price="<?= number_format($producte->preu, 2) ?>"
        data-id="<?= $producte->id ?>"
        data-stock="<?= $producte->estoc ?>"
        data-image="<?= $producte->imatge ? imageUrl($producte->imatge) : '' ?>"
        onclick="window.cartModule.addToCart('<?= htmlspecialchars($producte->nom) ?>', <?= $producte->preu ?>, <?= $producte->id ?>, <?= $producte->estoc ?>)">
        <i class="fas fa-cart-plus"></i> Afegir al carret
    </button>
    <a href="show.php?id=<?= $producte->id ?>" class="btn btn-info btn-sm">Veure detalls</a>
</div>
