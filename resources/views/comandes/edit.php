<?php
$fields = ['client_id', 'total', 'estat', 'direccio_enviament'];
$values = escapeArray(formDefaults($fields, $comanda ?? null));
$errors = escapeArray(session()->getFlash('errors', []));
?>

<div class="container mt-4">
    <h2>Editar Comanda</h2>

    <!-- Mostrar errores si existen -->
    <?php include __DIR__ . '/../partials/errors.php'; ?>

    <!-- Formulario de edición -->
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= $comanda->id ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit" class="btn btn-primary">Actualitzar</button>
        <a href="<?= previousUrl() ?>" class="btn btn-secondary">Cancel·lar</a>
    </form>
</div>
