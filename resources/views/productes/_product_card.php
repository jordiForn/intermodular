<div class="product-card">
    <?php if ($producte->imatge): ?>
        <img src="<?= imageUrl($producte->imatge, 300, 200) ?>" alt="<?= htmlspecialchars($producte->nom) ?>">
    <?php endif; ?>
    <h3><?= htmlspecialchars($producte->nom) ?></h3>
    <p><?= htmlspecialchars(substr($producte->descripcio, 0, 100)) . (strlen($producte->descripcio) > 100 ? '...' : '') ?></p>
    <p class="price"><?= number_format($producte->preu, 2, ",", ".") ?>€</p>
    <p>Estoc disponible: <?= number_format($producte->estoc, 0, ",", ".") ?></p>
    <div class="tooltip-container">
        <button
            type="button"
            class="add-to-cart-btn"
            data-name="<?= addslashes($producte->nom) ?>"
            data-price="<?= $producte->preu ?>"
            data-id="<?= $producte->id ?>"
            data-stock="<?= $producte->estoc ?>"
        >Afegir al Carret</button>
        <span class="tooltip-text">0 ítems - 0,00€</span>
    </div>
</div>
