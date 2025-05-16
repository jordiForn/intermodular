<div class="container mt-4">
    <h2 class="mb-4"><?= "Productes de la categoria: " . htmlspecialchars($categoria) ?></h2>

    <?php if (session()->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($productes)): ?>
        <p>No hi ha productes en aquesta categoria.</p>
    <?php else: ?>
        <?php if ($categoria == 'Terra i adobs'): ?>
            <div class="alert alert-info mb-4">
                <h5>Consell de jardineria</h5>
                <p>Una bona terra és la base d'un jardí saludable. Assegura't d'escollir el tipus de substrat adequat per a cada planta.</p>
            </div>
        <?php elseif ($categoria == 'Plantes i llavors'): ?>
            <div class="alert alert-info mb-4">
                <h5>Consell de jardineria</h5>
                <p>Recorda que cada planta té necessitats específiques de llum, aigua i nutrients. Consulta les nostres fitxes de cultiu per a més informació.</p>
            </div>
        <?php endif; ?>
        
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
                <a href="?categoria=<?= urlencode($categoria) ?>&page=<?= $page - 1 ?>" class="text-decoration-none me-4">&laquo; Anterior</a>
            <?php endif; ?>
    
            <span>Pàgina <?= $page ?> de <?= $totalPages ?></span>
    
            <?php if ($page + 1 <= $totalPages): ?>
                <a href="?categoria=<?= urlencode($categoria) ?>&page=<?= $page + 1 ?>" class="text-decoration-none ms-4">Següent &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
