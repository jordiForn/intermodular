<div class="container mt-4">
    <h2 class="mb-4"><?= "Resultats per '$q'" ?></h2>

    <?php if (session()->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($productes)): ?>
        <p>No s'han trobat resultats per "<?= htmlspecialchars($q) ?>".</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($productes as $producte): ?>
                <div class="col-md-4 mb-4">
                    <?php include __DIR__ . '/_product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    
         <!-- Paginación -->
         <div class="d-flex justify-content-center align-items-center mt-4">
            <?php if ($page > 1): ?>
                <a href="?q=<?= urlencode($q) ?>&page=<?= $page - 1 ?>" class="text-decoration-none me-4">&laquo; Anterior</a>
            <?php endif; ?>
    
            <span>Pàgina <?= $page ?> de <?= $totalPages ?></span>
    
            <?php if ($page + 1 <= $totalPages): ?>
                <a href="?q=<?= urlencode($q) ?>&page=<?= $page + 1 ?>" class="text-decoration-none ms-4">Següent &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
