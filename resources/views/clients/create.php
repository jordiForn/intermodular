<?php
$fields = ['nom', 'cognom', 'email', 'tlf', 'consulta', 'missatge', 'nom_login'];
$values = escapeArray(session()->getFlash('old', []));
$errors = escapeArray(session()->getFlash('errors', []));
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus"></i> Crear nou client
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Error Messages -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-circle"></i> Hi ha errors en el formulari:</h6>
                            <ul class="mb-0">
                                <?php foreach ($errors as $field => $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="/clients/store.php" method="POST" novalidate>
                        <?php include __DIR__ . '/_form.php'; ?>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="/admin/clients.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Tornar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Crear client
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
