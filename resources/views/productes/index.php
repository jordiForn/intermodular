<?php use App\Core\Auth; ?>

<div class="container mt-4">

    <h1 class="mb-4">Catàleg de Productes</h1>

    <!-- Mostrar mensajes de error y éxito si existen -->
    <?php include __DIR__ . '/../partials/messages.php'; ?>

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
            <a href="?page=<?= $page - 1 ?>" class="text-decoration-none me-4">&laquo; Anterior</a>
        <?php endif; ?>

        <span>Pàgina <?= $page ?> de <?= $totalPages ?></span>

        <?php if ($page + 1 <= $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="text-decoration-none ms-4">Següent &raquo;</a>
        <?php endif; ?>
    </div>
</div>
