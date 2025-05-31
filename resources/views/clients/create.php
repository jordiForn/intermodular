<?php
$fields = ['nom', 'cognom', 'email', 'tlf', 'consulta', 'missatge', 'nom_login'];
$values = escapeArray(formDefaults($fields, $client ?? null));
$errors = escapeArray(session()->getFlash('errors', []));
?>

<div class="container mt-4">
    <h2>Crear Client</h2>

    <!-- Mostrar errores si existen -->
    <?php include __DIR__ . '/../partials/errors.php'; ?>

    <!-- Formulario de creación -->
    <form action="store.php" method="POST">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit" class="btn btn-primary">Crear</button>
        <a href="<?= previousUrl() ?>" class="btn btn-secondary">Cancel·lar</a>
    </form>
</div>
