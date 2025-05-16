<?php
$fields = ['nom', 'descripcio', 'preu', 'estoc', 'categoria', 'imatge', 'detalls'];
$values = escapeArray(formDefaults($fields, $producte ?? null));
$errors = escapeArray(session()->getFlash('errors', []));
?>

<div class="container mt-4">
    <h2>Editar Producte</h2>

    <!-- Mostrar errores si existen -->
    <?php include __DIR__ . '/../partials/errors.php'; ?>

    <!-- Formulario de edición -->
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= $producte->id ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit" class="btn btn-primary">Actualitzar</button>
        <a href="<?= previousUrl() ?>" class="btn btn-secondary">Cancel·lar</a>
    </form>
</div>
